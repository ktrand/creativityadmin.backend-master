<?php

Route::group(['middleware' => 'cors'], function () {
    Route::post('login', 'Auth\AuthController@login');
    Route::post('register', 'Auth\AuthController@register');
    Route::get('/images/{path}', 'MediaController@getImage');
    Route::get('/images/blog/{path}', 'MediaController@getBlogImage');
    Route::get('/videos/{videoId}', 'MediaController@getVideo');

    Route::post('/push-notification', 'Auth\AuthController@sendPush');
    Route::post('auth/forgot_password', 'Auth\AuthController@forgotUserPassword');
    Route::post('auth/reset_password', 'Auth\AuthController@resetUserPassword');

    Route::namespace('Guest')->group( function () {
        Route::group(['prefix' => 'guest'], function () {
            Route::get('videos', 'GuestController@getVideos');
        });
        Route::get('/freevideo/{videoId}', 'GuestController@getVideo');
        Route::get('/posts', 'GuestController@getPosts');
        Route::get('/post/contents/{postId}', 'GuestController@getPostContents')->where(['post' => '[0-9]+']);
        Route::get('/recommendation-videos/{videoId}', 'GuestController@getRecommendationVideos');
    });
    Route::namespace('Web\Like')->group(function () {
        Route::group(['prefix' => 'like'], function () {
            Route::get('/count', 'LikeController@getLikesCount');
        });
    });

    Route::get('search/{query}', 'MediaController@search');
    Route::get('videos/by-category/{slug}', 'MediaController@getVideosByCategory');

    Route::get('/send', 'Auth\AuthController@sendPush');
});

Route::group(['middleware' => ['auth.jwt', 'cors']], function () {
    Route::post('/save-device-token', 'Auth\AuthController@saveToken');
    Route::get('logout', 'Auth\AuthController@logout');
    Route::post('user/edit-account-data', 'Auth\AuthController@updateAccountData');
    Route::get('user/verify-link', 'Auth\AuthController@sendVerifyLink');
    Route::post('user/verify-account', 'Auth\AuthController@verifyUserEmail');
    Route::get('statistics', 'HomeController@getStatistics');

    Route::namespace('Category')->group(function () {
        Route::group(['prefix' => 'category', 'middleware' => 'admin'], function () {
            Route::post('/', 'CategoryController@store');
            Route::get('/', 'CategoryController@getAll');
            Route::post('/{category_id}', 'CategoryController@update')->where(['category_id' => '[0-9]+']);
            Route::delete('/{category_id}', 'CategoryController@destroy')->where(['category_id' => '[0-9]+']);
            Route::patch('/delete_selected', 'CategoryController@destroySelected');
        });
    });

    Route::namespace('Comment')->group(function () {
        Route::group(['prefix' => 'comment'], function () {
            Route::post('/{videoId}', 'CommentController@store');
            Route::delete('/{commentId}', 'CommentController@destroy');
        });
    });

    Route::namespace('Video')->group(function () {
        Route::group(['prefix' => 'video', 'middleware' => 'admin'], function () {
            Route::post('/', 'VideoController@store');
            Route::post('/upload_video/{videoId}', 'VideoController@uploadVideo');
            Route::get('/', 'VideoController@getAll');
            Route::post('/{video_id}', 'VideoController@update');
            Route::delete('/{video_id}', 'VideoController@destroy');
            Route::put('/toggle_publish/{video_id}', 'VideoController@togglePublish');
//            Route::patch('/delete_selected', 'CategoryController@destroySelected');
        });
    });


    Route::namespace('Posts')->group(function () {
        Route::group(['prefix' => 'post', 'middleware' => 'admin'], function () {
            Route::post('/', 'PostController@store');
            Route::get('/', 'PostController@getAll');
            Route::post('/{post_id}', 'PostController@update')->where(['post_id' => '[0-9]+']);
            Route::delete('/{post_id}', 'PostController@destroy')->where(['post_id' => '[0-9]+']);
            Route::patch('/delete_selected', 'PostController@destroySelected');

            Route::get('/content/{postId}', 'PostContentController@getPostContent')->where(['post' => '[0-9]+']);
            Route::post('/contents/{postId}', 'PostContentController@storePostContents')->where(['postId' => '[0-9]+']);
            Route::post('/content/{post_id}', 'PostContentController@storePostContent')->where(['post_id' => '[0-9]+']);
            Route::post('/content/change/{post_content_id}', 'PostContentController@updatePostContent')->where(['post_content_id' => '[0-9]+']);
            Route::put('/toggle_publish/{post_id}', 'PostController@togglePublish');
            Route::post('/content/update-positions/{post_id}', 'PostContentController@updatePositions')->where(['post_id' => '[0-9]+']);
            Route::delete('/content/{post_content_id}', 'PostContentController@deleteContent')->where(['post_content_id' => '[0-9]+']);
        });
    });



    /*web routes*/
    Route::namespace('Web')->group(function () {
        Route::namespace('Like')->group(function () {
            Route::group(['prefix' => 'like'], function () {
                Route::put('/toggle-like', 'LikeController@toggle');
            });
        });

        Route::group(['prefix' => 'web'], function () {
            Route::namespace('Video')->group(function () {
                Route::group(['prefix' => 'video'], function () {
                    Route::get('/', 'VideoController@getAll');
                    Route::get('/liked', 'VideoController@getLikedVideos');
                });
            });
        });
    });







});
