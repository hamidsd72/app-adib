<?php 
  
Route::resource('forms', 'FormController');
Route::resource('job-report', 'JobReportController');
Route::resource('notification', 'NotificationController');
Route::get('/', 'GuestController@index')->name('home-goust');
Route::get('/pwa', 'GuestController@create')->name('home-guost-pwa');
Route::get('/app', 'HomeController@index')->name('index');
Route::get('/update-roll-call', 'HomeController@updateRollCall')->name('updateRollCall');
Route::get('/services/{cat_id}', 'HomeController@services')->name('services');
Route::get('/service/{id}/{slug}', 'HomeController@service')->name('service');
Route::get('/packages_category', 'HomeController@packages_category')->name('package.category');
Route::get('/packages', 'HomeController@packages')->name('packages');
Route::get('/package/{slug}', 'HomeController@package')->name('package');
Route::get('/job/create/{slug}', 'HomeController@job_create')->name('job_create');
Route::get('/offline/job/create/{id}/{created_at}/{description}', 'HomeController@offline_job_create')->name('offline_job_create');
Route::post('/job/stop', 'HomeController@job_stop')->name('job_stop');
Route::get('/reserve/{type}/{slug}', 'HomeController@reserve')->name('reserve');
Route::post('/validation-email', 'HomeController@validation_email')->name('validation.email');
Route::get('/login-package-buy/{slug}/{price_id?}', 'HomeController@login_package_buy')->name('login.package.buy');
Route::get('/package-buy', 'HomeController@package_buy')->name('package.buy');
Route::get('/off_check/{code}/{price}', 'HomeController@off_check')->name('off.check');
//bascket 
Route::get('/basket/view', 'BasketController@index')->name('basket_index');
Route::get('/add_basket/{id}/{type}', 'BasketController@add_basket')->name('add_basket');
Route::get('/level_1', 'BasketController@level_1')->name('basket.list');
Route::post('/level_2', 'BasketController@level_2')->name('basket.pay');
Route::get('/basket_del/{id}/{type}', 'BasketController@del_basket')->name('basket.del');
/*Route::get('/basket_del', 'BasketController@del_basket')->name('basket.del');*/

Route::get('report/user-transaction/{id}', 'TransactionController@index')->name('user-transaction-report');
Route::resource('user-transaction', 'TransactionController');
Route::get('ads/tours/{name}', 'TourAdsController@show')->name('ads-tours-show-guest');
Route::get('ads/tours/{name}/edit', 'TourAdsController@edit')->name('admin-ads-tours-filter');
Route::get('ads/tours/service-destroy/{id}', 'TourAdsController@destroy')->name('admin-ads-tours-service-destroy');
Route::get('ads/tours/filter/{id}', 'TourAdsController@index')->name('ads-tours-index-filter');
Route::resource('user-tours', 'TourAdsController');
Route::resource('sign-up-using-mobile', 'NewRegisterController');
Route::resource('my-user', 'UserController');
Route::resource('user-search', 'SearchController');
// Route::post('user/search/services', 'SearchController@search')->name('services-search');

// ticket 
Route::get('user/show/tikects', 'HomeController@tickets')->name('tickets'); 
Route::get('user/show/tikect/{id}', 'HomeController@show_ticket')->name('show-ticket'); 

//register
Route::get('user-register/{code}', 'RegisterController@register')->name('register'); 
Route::get('agent-register', 'RegisterController@agent')->name('agent.register');
Route::get('user-register', 'RegisterController@mobile')->name('mobile');
Route::post('mobile-post', 'RegisterController@mobile_post')->name('mobile.post');
Route::get('verify-code', 'RegisterController@verify')->name('verify');
Route::post('verify-code-post', 'RegisterController@verify_post')->name('verify.post');
Route::get('complete', 'RegisterController@complete')->name('complete');
Route::post('complete-post', 'RegisterController@complete_post')->name('complete.post');
Route::get('complete-agent', 'RegisterController@complete_agent')->name('complete.agent');
Route::post('complete-agent-post', 'RegisterController@complete_agent_post')->name('complete.agent.post');

//reset password
Route::get('user-reset-password', 'PasswordController@reset_password_show')->name('reset.password.show');
Route::post('user-reset-password-post', 'PasswordController@reset_password_post')->name('reset.password.post');
Route::get('verify-reset-password', 'PasswordController@reset_password_verify')->name('reset.password.verify');
Route::post('verify-reset-password-post', 'PasswordController@reset_password_verify_post')->name('reset.password.verify.post');
Route::get('new-password', 'PasswordController@new_password')->name('new.password');
Route::post('new-password-post', 'PasswordController@new_password_post')->name('new.password.post');

// contact us
Route::get('contact-us', 'ContactController@show')->name('contact.show');
// Route::post('contact-us-post', 'ContactController@form_post')->name('contact.post');
// Route::post('contact-us-post', 'TicketController@form_post')->name('contact.post');

// guide user
Route::get('about-us', 'AboutController@show')->name('about.show');

// guide user
Route::get('guide-user', 'GuideController@show')->name('guide.show');

// rules
Route::get('rules', 'RuleController@show')->name('rule.show');

// agent
Route::get('agent-rule', 'AgentController@show')->name('agent.rule.show');
Route::post('agent-request', 'AgentController@agent_request')->name('agent.request');

//zarin pal
Route::any('zarinpal-pay/{id}/{total}/{user}/{type}', 'ZarinpalController@pay')->name('zarinpal-pay-user');
Route::any('zarinpal-verify', 'ZarinpalController@verify')->name('verify_user');
//zarin pal new
Route::any('zarinpal-pay-new/{factor_id}/{user_id}', 'ZarinpalNewController@pay')->name('zarinpal.pay.new');
Route::any('zarinpal-verify-new', 'ZarinpalNewController@verify')->name('verify.new');

//refah
Route::any('refah-pay/{id}/{type}', 'RefahController@pay')->name('refah.pay');
Route::any('refah-verify', 'RefahController@verify')->name('refah.verify');


Route::view('questions','user.questions')->name('questions');
// Route::view('services','user.services')->name('services');

// work controller
Route::get('works/{id?}', 'WorkController@index')->name('works');
Route::get('work-search', 'WorkController@search')->name('work-search');
Route::get('work-create', 'WorkController@create')->name('work-create');
Route::post('work-store', 'WorkController@store')->name('work-store');
Route::get('work-edit/{id}', 'WorkController@edit')->name('work-edit');
Route::post('work-update/{id}', 'WorkController@update')->name('work-update');
Route::post('work-stop', 'WorkController@stop')->name('work-stop');

// TimeSheet
Route::post('timesheet-store', 'WorkTimesheetController@store')->name('timesheet-store');
Route::post('timesheet-pause', 'WorkTimesheetController@pause')->name('timesheet-pause');
Route::post('timesheet-stop', 'WorkTimesheetController@stop')->name('timesheet-stop');

Route::get('leave', 'ProfileController@leave')->name('leave');
Route::get('my_leave', 'ProfileController@my_leave')->name('my_leave');
Route::post('leave_send', 'ProfileController@leave_send')->name('leave_send');

// electronic dashboard
Route::get('help-list', 'HelpController@index')->name('help-index');
Route::get('helps/{type?}', 'HelpController@index')->name('helps');
Route::get('help-show/{id}', 'HelpController@show')->name('helps_show');
Route::get('help-edit/{id}', 'HelpController@edit')->name('helps_edit');
Route::post('help_store', 'HelpController@store')->name('help_store');
Route::post('help_update/{id}', 'HelpController@update')->name('help_update');
Route::post('help_comment_store', 'HelpController@comment_store')->name('help_comment_store');
Route::get('help_destroy', 'HelpController@destroy')->name('help_destroy');
Route::post('help_done_job_store', 'HelpController@done_job_store')->name('help_done_job_store');

//time_login_adib
Route::get('time-login-adib/{status}', 'TimeAdibController@index')->name('time-login-index');
Route::get('time-login-adib-status/{id}/{status}', 'TimeAdibController@status')->name('time-login-status');
Route::get('time-login-adib-create', 'TimeAdibController@create')->name('time-login-create');
Route::post('time-login-adib-create/post', 'TimeAdibController@create_post')->name('time-login-create-post');

// Ticket
Route::resource('ticket', 'TicketController');
Route::get('ticket/create/customer', 'TicketController@customer_show')->name('ticket.customer.show');
Route::post('ticket/customer/post', 'TicketController@customer_send')->name('ticket.customer.send');
Route::post('comment_store', 'TicketController@comment_store');
Route::post('comment_update/{id?}', 'TicketController@comment_update')->name('comment-update');
Route::get('comment-confirm/{id?}', 'TicketController@comment_confirm')->name('comment-confirm');
Route::post('p-comment-confirm/{id?}', 'TicketController@p_comment_confirm')->name('p-comment-confirm');
Route::post('ticket-search', 'TicketController@ticket_search')->name('ticket-search');
Route::get('ticket_closed/{id}', 'TicketController@close')->name('ticket_closed');
Route::get('ticket_doing/{id}', 'TicketController@doing')->name('ticket_doing');
Route::post('ticket_finished', 'TicketController@finished')->name('ticket_finished');
Route::get('invoice_confirm/{id}', 'TicketController@confirm')->name('invoice_confirm');
Route::get('invoices', 'TicketController@invoices')->name('invoices');
Route::get('ticket-answered', 'TicketController@answered')->name('ticket-answered');

Route::get('old_ticket/', 'TicketController@index2')->name('old_ticket');
Route::get('auto_closed', 'TicketController@auto_closed')->name('auto_closed');
Route::get('reference/{id}', 'TicketController@reference')->name('reference');
Route::get('reference-move/{id}', 'TicketController@reference_move')->name('reference-move');

//to do list
Route::resource('todo-list-ref', 'TodoList\TodoListRefController');
Route::get('todo-list-ref-user/{id}/list', 'TodoList\TodoListRefController@user_group')->name('todo.list.ref.user.list');
Route::post('todo-list-ref-user/{id}/store', 'TodoList\TodoListRefController@user_group_store')->name('todo.list.ref.user.store');
Route::post('todo-list-ref-user/{id}/sort', 'TodoList\TodoListRefController@user_group_sort')->name('todo.list.ref.user.sort');
Route::delete('todo-list-ref-user/{id}/delete', 'TodoList\TodoListRefController@user_group_delete')->name('todo.list.ref.user.delete');

Route::resource('todo-list-category', 'TodoList\TodoListCatController');
Route::get('todo-list-user-cc/{id}/list', 'TodoList\TodoListCatController@user_cc')->name('todo.list.cc.user.list');
Route::post('todo-list-user-cc/{id}/store', 'TodoList\TodoListCatController@user_cc_store')->name('todo.list.cc.user.store');
Route::delete('todo-list-user-cc/{id}/delete', 'TodoList\TodoListCatController@user_cc_delete')->name('todo.list.cc.user.delete');

Route::resource('todo-list', 'TodoList\TodoListController');

Route::get('todo-list/{id}/status/{type}', 'TodoList\TodoListController@status')->name('todo.list.status');
Route::post('todo-list/{id}/report/{type}', 'TodoList\TodoListController@report')->name('todo.list.report');
Route::post('todo-list/{id}/ref', 'TodoList\TodoListController@ref')->name('todo.list.ref');
