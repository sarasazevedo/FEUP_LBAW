<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LikePostController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\FooterController;
use App\Http\Controllers\LikeCommentController;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ManageUsersController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RequestFollowController;
use App\Http\Controllers\GroupController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home

//Posts
//Posts
Route::controller(PostController::class)->group(function () {
    Route::get('/', 'list')->name('posts');
    Route::get('/post/{id}', 'show');
});

Route::controller(PostController::class)->group(function () {
    Route::post('/api/posts/create', 'create')->name('posts.create');
    Route::put('/posts/{id}', 'update')->name('posts.update');
});

Route::get('/posts/{id}', [PostController::class, 'show'])->name('posts.show');

//Restaurants
Route::controller(RestaurantController::class)->group(function () {
    Route::get('/restaurants/search', [RestaurantController::class, 'search']);
});


// Authentication
Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'authenticate');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});


Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

// Footer
Route::controller(FooterController::class)->group(function () {
    Route::get('/about', 'about')->name('about');
    Route::get('/contacts', 'contacts')->name('contacts');
    Route::get('/main-features', 'mainFeatures')->name('main_features');
    Route::get('/faq', 'faq')->name('faq');
});

// Profile 
Route::get('/profile/{id}', [ProfileController::class, 'show'])->name('profile.show');
Route::post('/profile/{id}/follow', [ProfileController::class, 'follow'])->name('follow');
Route::post('/profile/{id}/unfollow', [ProfileController::class, 'unfollow'])->name('unfollow');
Route::post('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
Route::post('/profile/{id}/cancel-follow-request', [ProfileController::class, 'cancelFollowRequest'])->name('cancel-follow-request');
Route::delete('/profile/{id}/delete', [ProfileController::class, 'destroy'])->name('profile.delete')->middleware('auth');
Route::get('/profile/{id}/followers', [ProfileController::class, 'getFollowers'])->name('profile.followers');

Route::get('/profile/{id}/following', [ProfileController::class, 'getFollowing'])->name('profile.following');
//Posts
Route::post('/like-post/{id}', [LikePostController::class, 'likePost'])->name('like.post');
Route::post('/post/{id}/comment', [CommentController::class, 'addComment'])->name('post.comment');
Route::get('/post/{id}/comments', [CommentController::class, 'loadComments'])->name('post.comments');
Route::get('/likes/{postId}', [LikePostController::class, 'getLikesByPostId']);
Route::delete('/post/{id}', [PostController::class, 'destroy'])->name('post.destroy')->middleware('auth');
Route::post('/comment/{id}/edit', [CommentController::class, 'editComment'])->name('comment.edit');
Route::delete('/comment/{id}/delete', [CommentController::class, 'deleteComment'])->name('comment.delete');
Route::get('/comments/{postId}/count', [CommentController::class, 'getCommentsCount']);


// Manage Users
Route::middleware(['auth'])->group(function () {
    Route::get('/manage-users', [ManageUsersController::class, 'index'])->name('manage.users');
    Route::get('/manage-users/{id}', [ManageUsersController::class, 'show'])->name('manage.users.show');
    Route::post('/manage-users/block/{id}', [ManageUsersController::class, 'blockUser'])->name('manage.users.block');
    Route::delete('/manage-users/delete/{id}', [ManageUsersController::class, 'deleteUser'])->name('manage.users.delete');
});

// Request Follow
Route::get('/requests', [RequestFollowController::class, 'showRequests'])->name('requests.show');
Route::post('/requests/accept/{requesterId}', [RequestFollowController::class, 'acceptRequest'])->name('requests.accept');
Route::post('/requests/reject/{requesterId}', [RequestFollowController::class, 'rejectRequest'])->name('requests.reject');

Route::post('/like-comment/{id}', [LikeCommentController::class, 'likeComment']);
// Search 
Route::get('/search', [SearchController::class, 'search'])->name('search');

// Groups
Route::get('/groups', [GroupController::class, 'index'])->name('groups.index');
Route::post('/groups', [GroupController::class, 'store'])->name('groups.store');
Route::get('/groups/{id}', [GroupController::class, 'show'])->name('groups.show');
Route::post('/groups/{id}/join', [GroupController::class, 'joinGroup'])->name('groups.join');
Route::post('/groups/{id}/leave', [GroupController::class, 'leaveGroup'])->name('groups.leave');
Route::post('/groups/{id}/request-join', [GroupController::class, 'requestJoinGroup'])->name('groups.requestJoin');
Route::post('/groups/{id}/accept/{clientId}', [GroupController::class, 'acceptJoinRequest'])->name('groups.acceptJoin');
Route::post('/groups/{id}/decline/{clientId}', [GroupController::class, 'declineJoinRequest'])->name('groups.declineJoin');
Route::get('/groups/{id}/members', [GroupController::class, 'getMembers'])->name('groups.members');
Route::post('/groups/{id}/cancel-request', [GroupController::class, 'cancelJoinRequest'])->name('groups.cancelRequest');


Route::post('/groups/{groupId}/invite/{userId}', [GroupController::class, 'inviteUser']);
Route::post('/groups/{groupId}/cancel-invite/{userId}', [GroupController::class, 'cancelInviteUser'])->name('groups.cancelInvite');
Route::post('/invitations/{invitationId}/accept', [GroupController::class, 'acceptInvitation']);
Route::post('/invitations/{invitationId}/reject', [GroupController::class, 'rejectInvitation']);


Route::get('/invitations', [GroupController::class, 'getInvitations'])->name('invitations');

Route::post('/groups/{id}/update-description', [GroupController::class, 'updateDescription'])->name('groups.updateDescription');
Route::post('/groups/{groupId}/members/{memberId}/remove', [GroupController::class, 'removeMember'])->name('groups.removeMember');
Route::get('/groups/{groupId}/users', [GroupController::class, 'getPaginatedUsers'])->name('groups.paginatedUsers');
// Forgot Password Page
Route::get('/forgot_password', function () {
    return view('auth.forgot_password');
})->name('forgot.password');

// Send Password Reset Email
Route::post('/send', [MailController::class, 'send'])->name('password.send');

// Display Reset Password Form with Decoded Email
Route::get('/recover_password/{email}', function ($email) {
    return view('auth.recover_password', ['email' => base64_decode($email)]);
})->name('password.reset.email');

// Show Password Reset Form
Route::get('/password-reset', [PasswordController::class, 'showResetForm'])->name('password.reset');

// Handle Password Reset Request
Route::post('/password-reset-submit', [PasswordController::class, 'reset'])->name('password.update');

Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('/notifications/{id}/mark-as-viewed', [NotificationController::class, 'markAsViewed'])->name('notifications.markAsViewed');
Route::get('/notifications/count/{userId}', [NotificationController::class, 'getUnreadCount']);
Route::post('/appeal_unblock', [NotificationController::class, 'appealUnblock'])->name('appeal_unblock');

Route::post('/posts/{id}/pin', [PostController::class, 'pin'])->name('posts.pin');
Route::post('/posts/{id}/unpin', [PostController::class, 'unpin'])->name('posts.unpin');