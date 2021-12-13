<?php

namespace App\Http\Controllers\Auth;

use App\Modules\Auth\Models\User;
use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Requests\RegisterRequest;
use App\Modules\Auth\Requests\UpdateAccountDataRequest;
use App\Modules\Auth\UserService;
use App\Modules\Users\Jobs\SendVerificationLink;
use App\Modules\Users\Requests\ResetPasswordRequest;
use App\Modules\Users\UseCases\ForgetPassword;
use App\Modules\Users\UseCases\VerifyEmail;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Exceptions\{JWTException};

class AuthController extends Controller
{
    protected $auth;
    protected $forgotPassword;
    protected $serverKey;
    protected $endpoint;

    public function __construct(JWTAuth $auth, ForgetPassword $forgotPassword)
    {
        $this->auth = $auth;
        $this->forgotPassword = $forgotPassword;
        $this->serverKey = config('app.firebase_server_key');
        $this->endpoint = "https://fcm.googleapis.com/fcm/send";
    }

    public function login(LoginRequest $request)
    {
        try {
            if (!$token = $this->auth->attempt($request->only('email', 'password'))) {
                return response()->json([
                    'errors' => [
                        'root' => 'Could not sign you in with those details.'
                    ]
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'errors' => [
                    'root' => 'Failed.'
                ]
            ], $e->getStatusCode());
        }

        return response()->json([
            'data' => $request->user(),
            'meta' => [
                'token' => $token
            ]
        ], 200);
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $token = $this->auth->attempt($request->only('email', 'password'));

        return response()->json([
            'data' => $user,
            'meta' => [
                'token' => $token
            ]
        ], 200);
    }

    public function logout()
    {
        $this->auth->invalidate($this->auth->getToken());

        return response(null, 200);
    }

    public function user(Request $request)
    {
        return response()->json([
            'data' => $request->user(),
        ]);
    }

    public function updateAccountData(UpdateAccountDataRequest $request)
    {
        $user = Auth::user();
        if($request->file('img')){
            $imgPath = $request->file('img')->store('images');
            $user->img = $imgPath;
        }
        $user->name = $request->name;
//        $user->email = $request->email;

        $user->update();

        return compact('user');
    }

    public function forgotUserPassword(Request $request) :JsonResponse
    {
        $this->validate($request, [
            'email' => [
                'required',
                'email',
                Rule::exists('users')->where(function ($query) use ($request) {
                    $query->where('email', $request->email);
                }),
            ],
        ],
            ['email.exists' => 'Пользователь не найден :(']);

        return response()->json(['forgetPassword' => $this->forgotPassword->forgotPassword($request->email)]);
    }

    public function resetUserPassword(ResetPasswordRequest $request) :JsonResponse
    {
        $requestOnly = $request->only(['email', 'password', 'token']);

        return response()->json(['resetPassword' => $this->forgotPassword->resetPassword($requestOnly)]);
    }

    public function verifyUserEmail(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'verify_token' => 'required'
        ]);
        $user = User::where('email', $request->email)->where('verify_token', $request->verify_token)->first();
        if (!$user) {
            return response()->json("Неправильные данные!", 404);
        }
        VerifyEmail::verifyAccount($user);

        return response()->json(['data'=>$user, 'message'=>'Успешная активация!'], 200);
    }

    /**
     * @return JsonResponse
     */
    public function sendVerifyLink()
    {
        $user = Auth::user();

        if ($user->verified) {
            return response()->json("Аккаунт уже активирован!", 200);
        }

        if ($user->verify_token !== null) {
            return response()->json("Сообщение уже отправлено, пожалуйста проверьте почту!", 200);
        }

        $job = (new SendVerificationLink($user))->delay(Carbon::now()->addSeconds(2));

        dispatch($job);

        return response()->json("Мы отправили ссылку активации в вашу почту!", 200);
    }

    public function saveToken (Request $request)
    {
        User::whereId(Auth::user()->id)->update(['device_token'=>$request->token]);
        return response()->json(['token saved successfully.']);
    }

    public function sendPush ()
    {
        $firebaseToken = User::whereNotNull('device_token')->pluck('device_token')->all();

        $headers = [
            'Authorization' => 'key='.$this->serverKey,
            'Content-Type'  => 'application/json',
        ];
        $fields = [
            "registration_ids" => $firebaseToken,
            "notification" => [
                "title" => 'Новое уведомление',
                "body" => 'Creativity уведомляет вас посещать сайт creativity.tj',
                "icon" => "https://www.onlygfx.com/wp-content/uploads/2017/12/grunge-yes-no-icon-1-902x1024.png",
            ]
        ];

        $fields = json_encode ( $fields );

        $client = new Client();

        try{
            $request = $client->post($this->endpoint,[
                'headers' => $headers,
                "body" => $fields,
            ]);
            $response = $request->getBody();
            return $response;
        }
        catch (GuzzleException $e){
            return $e;
        }
    }
}
