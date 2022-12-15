<?php
Route::get('/', 'AdminController@index')->name('index');
Route::resource('off-day', 'OffDayController');
Route::resource('job-report', 'JobReportController');
Route::resource('roll-call', 'RollCallController');
Route::post('job-report/adminAddReport', 'JobReportController@adminAddReport')->name('job-report-add-report');
Route::get('job-report/map/{id}', 'JobReportController@map')->name('job-report-show-map');
Route::get('job-report/filter/{id}', 'JobReportController@index')->name('job-report.filter');
Route::resource('jobs', 'JobController');
Route::get('jobs/force/delete/{id}', 'JobController@destroy')->name('jobs.force.delete');
Route::resource('request', 'RequestController');
Route::get('request/force/delete/{id}', 'RequestController@destroy')->name('request.force.delete');
Route::resource('network-setting', 'SettingController');
Route::resource('form-price', 'FormPriceController');
Route::get('form-price/force/delete/{id}', 'FormPriceController@destroy')->name('form-price.force.delete');
Route::resource('permission', 'PermissionController');
Route::resource('notification', 'NotificationController');
Route::resource('ads-tours', 'TourismController');
Route::resource('forms', 'FormController');
Route::resource('ads-tours-album', 'AlbumController');
Route::get('ads-tours/{id}/destroy', 'TourismController@destroy')->name('ads-tours-destroy-item');
Route::get('ads-tours/album/{id}/destroy', 'AlbumController@destroy')->name('ads-tours-album-destroy-item');
//destroy notification
Route::get('notification/destroy/item/{id}', 'NotificationController@destroy')->name('notification.item.destroy');;
// setting
Route::get('setting-create/{id}', 'SettingController@setting_create')->name('setting.create');
Route::get('setting-edit/{id?}', 'SettingController@edit')->name('setting.edit');
Route::post('setting-update/{id}', 'SettingController@update')->name('setting.update');
Route::post('setting-store/new', 'SettingController@setting_store')->name('setting.store');
Route::get('setting-update/destroy/{id}', 'SettingController@destroy')->name('setting.destroy');

// about
Route::resource('about', 'AboutController');
// Route::get('about-edit', 'AboutController@edit')->name('about.edit');
// Route::post('about-update/{id}', 'AboutController@update')->name('about.update');

// rule
Route::get('rule-edit', 'RuleController@edit')->name('rule.edit');
Route::post('rule-update/{id}', 'RuleController@update')->name('rule.update');
 
// guide
Route::get('guide-edit', 'GuideController@edit')->name('guide.edit');
Route::post('guide-update/{id}', 'GuideController@update')->name('guide.update');
Route::get('about-destroy/{id}', 'GuideController@destroy')->name('about.destroy');
// contact
Route::get('contact-list/{type?}', 'ContactController@index')->name('contact.list');
Route::post('contact-send-email/{id}', 'ContactController@send_email')->name('contact.send.email');
Route::post('contact-send-ticket/{id}', 'ContactController@send_ticket')->name('contact.send.ticket');
Route::get('contact-destroy/{id}', 'ContactController@destroy')->name('contact.destroy');
 
// slider
Route::get('slider-list', 'SliderController@index')->name('slider.list');
Route::get('slider-create', 'SliderController@create')->name('slider.create');
Route::post('slider-store', 'SliderController@store')->name('slider.store');
Route::get('slider-edit/{id}', 'SliderController@edit')->name('slider.edit');
Route::post('slider-update/{id}', 'SliderController@update')->name('slider.update');
Route::post('slider-sort/{id}', 'SliderController@sort')->name('slider.sort');
Route::get('slider-destroy/{id}', 'SliderController@destroy')->name('slider.destroy');

// off_code
Route::get('off-list', 'OffController@index')->name('off.list');
Route::get('off-create', 'OffController@create')->name('off.create');
Route::post('off-store', 'OffController@store')->name('off.store');
Route::get('off-edit/{id}', 'OffController@edit')->name('off.edit');
Route::post('off-update/{id}', 'OffController@update')->name('off.update');
Route::get('off-destroy/{id}', 'OffController@destroy')->name('off.destroy');
Route::get('off-active/{id}/{type}', 'OffController@active')->name('off.active');

// customer
Route::get('customer-list', 'CustomController@index')->name('customer.list');
Route::get('customer-create', 'CustomController@create')->name('customer.create');
Route::post('customer-store', 'CustomController@store')->name('customer.store');
Route::get('customer-edit/{id}', 'CustomController@edit')->name('customer.edit');
Route::get('customer-active/{id}/{type}', 'CustomController@active')->name('customer.active');

Route::post('customer-update/{id}', 'CustomController@update')->name('customer.update');
Route::get('customer-destroy/{id}', 'CustomController@destroy')->name('customer.destroy');

// profile
Route::get('profile-show', 'ProfileController@show')->name('profile.show');
Route::get('profile-edit', 'ProfileController@edit')->name('profile.edit');
Route::get('password-edit', 'ProfileController@password_edit')->name('password.edit');
Route::post('profile-update/{id}', 'ProfileController@update')->name('profile.update');
Route::post('password-update/{id}', 'ProfileController@password_update')->name('password.update');

// user
Route::post('user/role/update', 'UserController@userRole')->name('user-role.update');
Route::get('user-list/{type?}', 'UserController@index')->name('user.list');
Route::get('user-show/{id}', 'UserController@show')->name('user.show');
Route::get('user-create', 'UserController@create')->name('user.create');
Route::post('user-store', 'UserController@store')->name('user.store');
Route::get('user-edit/{id}', 'UserController@edit')->name('user.edit');
Route::post('user-update/{id}', 'UserController@update')->name('user.update');
Route::get('user-destroy/{id}', 'UserController@destroy')->name('user.destroy');
Route::get('user-active/{id}/{type}', 'UserController@active')->name('user.active');
Route::get('fastLogin/{id}', 'UserController@fastLogin')->name('user.fastLogin');

// agent
Route::get('agent-list', 'AgentController@index')->name('agent.list');
Route::get('agent-show/{id}', 'AgentController@show')->name('agent.show');
Route::get('agent-create', 'AgentController@create')->name('agent.create');
Route::post('agent-store', 'AgentController@store')->name('agent.store');
Route::get('agent-edit/{id}', 'AgentController@edit')->name('agent.edit');
Route::post('agent-update/{id}', 'AgentController@update')->name('agent.update');
Route::get('agent-destroy/{id}', 'AgentController@destroy')->name('agent.destroy');

// marketer
Route::get('marketer-re_activate/{id}', 'MarteterController@re_activate')->name('marketer.re_activate');
Route::get('marketer-list', 'MarteterController@index')->name('marketer.list');
Route::get('marketer-show/{id}', 'MarteterController@show')->name('marketer.show');
Route::post('marketer-store', 'MarteterController@store')->name('marketer.store');
Route::get('marketer-create', 'MarteterController@create')->name('marketer.create');
Route::get('marketer-edit/{id}', 'MarteterController@edit')->name('marketer.edit');
Route::post('marketer-update/{id}', 'MarteterController@update')->name('marketer.update');
Route::get('marketer-destroy/{id}', 'MarteterController@destroy')->name('marketer.destroy');
Route::get('marketer-active/{id}/{type}', 'MarteterController@active')->name('marketer.active');

// agent-request
Route::get('agent-request-list', 'AgentRequestController@index')->name('agent.request.list');
Route::get('agent-request-show/{id}', 'AgentRequestController@show')->name('agent.request.show');
Route::get('agent-request-status/{type}/{id}', 'AgentRequestController@status')->name('agent.request.status');
Route::post('agent-request-active/{id}', 'AgentRequestController@active')->name('agent.request.active');

// service cat
Route::get('service-category-list', 'ServiceCategoryController@index')->name('service.category.list');
Route::get('service-category-create', 'ServiceCategoryController@create')->name('service.category.create');
Route::post('service-category-store', 'ServiceCategoryController@store')->name('service.category.store');
Route::get('service-category-edit/{id}', 'ServiceCategoryController@edit')->name('service.category.edit');
Route::post('service-category-update/{id}', 'ServiceCategoryController@update')->name('service.category.update');
Route::get('service-category-destroy/{id}', 'ServiceCategoryController@destroy')->name('service.category.destroy');
Route::get('service-category-active/{id}/{type}', 'ServiceCategoryController@active')->name('service.category.active');

// service
Route::get('service-list', 'ServiceController@index')->name('service.list');
Route::get('service-create/{type}', 'ServiceController@create')->name('service.create'); 
Route::post('service-store', 'ServiceController@store')->name('service.store');
Route::get('service-edit/{id}', 'ServiceController@edit')->name('service.edit');
Route::post('service-update/{id}', 'ServiceController@update')->name('service.update');
Route::get('service-destroy/{id}', 'ServiceController@destroy')->name('service.destroy');
Route::get('service-active/{id}/{type}', 'ServiceController@active')->name('service.active');
Route::get('service-order/{from}/{to}', 'ServiceController@order')->name('service.order');

// service package
Route::get('service-package-list', 'ServicePackageController@index')->name('service.package.list');
Route::get('service-package-filter/{id}', 'ServicePackageController@show')->name('service.package.filter');
Route::get('service-package-create', 'ServicePackageController@create')->name('service.package.create');
Route::post('service-package-store', 'ServicePackageController@store')->name('service.package.store');
Route::get('service-package-edit/{id}', 'ServicePackageController@edit')->name('service.package.edit');
Route::post('service-package-update/{id}', 'ServicePackageController@update')->name('service.package.update');
Route::post('sort-by-join', 'ServicePackageController@sort_by_join')->name('sort.by.join');
Route::get('service-package-destroy/{id}', 'ServicePackageController@destroy')->name('service.package.destroy');
Route::get('service-package-active/{id}/{type}', 'ServicePackageController@active')->name('service.package.active');

// service learn
Route::get('service-learn-list', 'ServiceController@learn_index')->name('service.learn.list');
Route::get('service-learn-create', 'ServiceController@learn_create')->name('service.learn.create');
Route::post('service-learn-store', 'ServiceController@learn_store')->name('service.learn.store');
Route::get('service-learn-edit/{id}', 'ServiceController@learn_edit')->name('service.learn.edit');
Route::post('service-learn-update/{id}', 'ServiceController@learn_update')->name('service.learn.update');
Route::get('service-learn-destroy/{id}', 'ServiceController@learn_destroy')->name('service.learn.destroy');
Route::get('service-learn-active/{id}/{type}', 'ServiceController@learn_active')->name('service.learn.active');
// learn package cat
Route::get('learn-package-category-list', 'LearnPackageCategoryController@index')->name('learn.package.category.list');
Route::get('learn-package-category-create', 'LearnPackageCategoryController@create')->name('learn.package.category.create');
Route::post('learn-package-category-store', 'LearnPackageCategoryController@store')->name('learn.package.category.store');
Route::get('learn-package-category-edit/{id}', 'LearnPackageCategoryController@edit')->name('learn.package.category.edit');
Route::post('learn-package-category-update/{id}', 'LearnPackageCategoryController@update')->name('learn.package.category.update');
Route::get('learn-package-category-destroy/{id}', 'LearnPackageCategoryController@destroy')->name('learn.package.category.destroy');


// service  learn package
Route::get('service-learn-package-list', 'ServicePackageController@learn_index')->name('service.learn.package.list');
Route::get('service-learn-package-create', 'ServicePackageController@learn_create')->name('service.learn.package.create');
Route::post('service-learn-package-store', 'ServicePackageController@learn_store')->name('service.learn.package.store');
Route::get('service-learn-package-edit/{id}', 'ServicePackageController@learn_edit')->name('service.learn.package.edit');
Route::post('service-learn-package-update/{id}', 'ServicePackageController@learn_update')->name('service.learn.package.update');
Route::post('learn-sort-by-join', 'ServicePackageController@learn_sort_by_join')->name('learn.sort.by.join');
Route::get('service-learn-package-destroy/{id}', 'ServicePackageController@learn_destroy')->name('service.learn.package.destroy');
Route::get('service-learn-package-active/{id}/{type}', 'ServicePackageController@learn_active')->name('service.learn.package.active');

//package video
Route::get('service-package-video-list/{id}', 'ServicePackageVideoController@index')->name('service.package.video.list');
Route::post('service-package-video-store/{id}', 'ServicePackageVideoController@store')->name('service.package.video.store');
Route::get('service-package-video-destroy/{id}', 'ServicePackageVideoController@destroy')->name('service.package.video.destroy');
Route::post('service-package-video-sort/{id}', 'ServicePackageVideoController@sort')->name('service.package.video.sort');
Route::get('service-package-video-active/{id}/{type}', 'ServicePackageVideoController@active')->name('service.package.video.active');

// package price
Route::get('service-package-price-list/{id}', 'ServicePackagePriceController@index')->name('service.package.price.list');
Route::post('service-package-price-store/{id}/{type}', 'ServicePackagePriceController@store')->name('service.package.price.store');
Route::get('service-package-price-destroy/{id}', 'ServicePackagePriceController@destroy')->name('service.package.price.destroy');
Route::get('service-package-price-active/{id}/{type}', 'ServicePackagePriceController@active')->name('service.package.price.active');

// service buy
Route::get('service-buy-list', 'ServiceBuyController@index')->name('service.buy.list');
Route::get('service-buy-active/{id}/{type}', 'ServiceBuyController@active')->name('service.buy.active');

// transaction
Route::get('report-transaction-list', 'TransactionController@index')->name('report.transaction.list');
Route::get('report-transaction-create', 'TransactionController@create')->name('report.transaction.create');
Route::get('report-transaction-search', 'TransactionController@search')->name('report.transaction.search');

//  service level
Route::get('service-level-list/{s_id}', 'ServiceLevelController@index')->name('service.level.list');
Route::get('service-level-create/{s_id}', 'ServiceLevelController@create')->name('service.level.create');
Route::post('service-level-store/{s_id}', 'ServiceLevelController@store')->name('service.level.store');
Route::get('service-level-edit/{id}', 'ServiceLevelController@edit')->name('service.level.edit');
Route::post('service-level-update/{id}', 'ServiceLevelController@update')->name('service.level.update');
Route::get('service-level-destroy/{id}', 'ServiceLevelController@destroy')->name('service.level.destroy');

//  service query
Route::get('service-query-list/{l_id}', 'ServiceQueryController@index')->name('service.query.list');
Route::get('service-query-create/{l_id}', 'ServiceQueryController@create')->name('service.query.create');
Route::post('service-query-store/{l_id}', 'ServiceQueryController@store')->name('service.query.store');
Route::get('service-query-edit/{id}', 'ServiceQueryController@edit')->name('service.query.edit');
Route::post('service-query-update/{id}', 'ServiceQueryController@update')->name('service.query.update');
Route::get('service-query-destroy/{id}', 'ServiceQueryController@destroy')->name('service.query.destroy');
Route::get('service-query-active/{id}/{type}', 'ServiceQueryController@active')->name('service.query.active');

//  service plus
Route::get('service-plus-list/{p_id}', 'ServicePlusController@index')->name('service.plus.list');
Route::get('service-plus-create/{p_id}', 'ServicePlusController@create')->name('service.plus.create');
Route::post('service-plus-store/{p_id}', 'ServicePlusController@store')->name('service.plus.store');
Route::get('service-plus-edit/{id}', 'ServicePlusController@edit')->name('service.plus.edit');
Route::post('service-plus-update/{id}', 'ServicePlusController@update')->name('service.plus.update');
Route::get('service-plus-destroy/{id}', 'ServicePlusController@destroy')->name('service.plus.destroy');
Route::get('service-plus-active/{id}/{type}', 'ServicePlusController@active')->name('service.plus.active');

// meta
Route::get('meta-list', 'MetaController@index')->name('meta.list');
Route::get('meta-create', 'MetaController@create')->name('meta.create');
Route::post('meta-store', 'MetaController@store')->name('meta.store');
Route::get('meta-edit/{id}', 'MetaController@edit')->name('meta.edit');
Route::post('meta-update/{id}', 'MetaController@update')->name('meta.update');
Route::get('meta-destroy/{id}', 'MetaController@destroy')->name('meta.destroy');
Route::get('meta-active/{id}/{type}', 'MetaController@active')->name('meta.active');


// visitlog
Route::get('visit_log', 'VisitLogController@index')->name('visit.log');


// Ticket
Route::resource('workhour-index', 'WorkHourController');
Route::get('workhour-dashboard', 'WorkHourController@dashboard')->name('workhour-dashboard');
Route::get('workhour-fetch', 'WorkHourController@fetch')->name('workhour-fetch');
Route::get('workhour-fetch-export-excel', 'WorkHourController@exportExcel')->name('exportExcelFetch');
Route::post('workhour-finalize', 'WorkHourController@finalize');
Route::post('workhour_edit/{id}', 'WorkHourController@workhour_edit');
Route::get('workhour-income', 'WorkHourController@income_store');
Route::get('workhour-incomes', 'WorkHourController@incomes');
