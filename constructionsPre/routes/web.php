<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () { 
    return view('welcome');
});
  Route::get('/dashboard', 'DashboardController@dashboard')->name('dashboard');

  /* ROUTES */
  Auth::routes();

  /**
   * Prefixed Admin Routes Starts here
   */
  Route::group(['middleware' => ['auth']], function () {
  /*
    Manage menu Module Routes
  */
  Route::get('/manage/menu', 'AdminMenuController@manageMenu')->name('manage-menu');
  Route::get('/manage/menu/{id}', 'AdminMenuController@manageMenu')->name('manage-menu-edit');
  Route::post('/manage/menu/save', 'AdminMenuController@manageMenu')->name('menu-save');	
  Route::get('/manage/menu/delete/{id}', 'AdminMenuController@menuDelete')->name('menu-delete');
  Route::get('/mail/send', 'AdminMenuController@send')->name('mail-send');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth']], function () {
  /**
   * Tanmaya Patra
   * Accounting Module
   */ 
  Route::get('/balance-sheet', 'AdminCashFlowController@balanceSheet');
  /**
    * By : Mrutyunjay
    * Date : 04 june 2018
    * Info : Admin property Module 
    */
  Route::get('/dashboard', 'DashboardController@dashboard')->name('dashboard');
  Route::post('/enquiry/save', 'DashboardController@enquiryadd')->name('enquiry-add');
  //------------------------properties module-----------------------
  Route::get('/properties/add', 'AdminPropertyController@properties')->name('property-add');
  Route::post('/statelistaccordingtocountry', 'AjaxStateController@AjaxState')->name('state-choose');
  Route::post('/citylistaccordingtocountry', 'AjaxStateController@AjaxCity')->name('city-choose');
  Route::post('/citylistaccordingtocountry', 'AjaxStateController@AjaxCity')->name('city-choose');
  Route::post('/properties/ajax/remove/installment', 'AdminPropertyController@deleteInstallment')->name('city-choose');

  Route::get('/properties/list', 'AdminPropertyController@list')->name('property-list');
  Route::get('/properties/edit/{id}', 'AdminPropertyController@properties')->name('property-edit');
  Route::get('/properties/trash/{id}', 'AdminPropertyController@propertyTrash')->name('property-trash');
  Route::post('/properties/save', 'AdminPropertyController@properties')->name('property-save');


  /**
   * Customer Purchase Module
   */
  Route::get('/customer/enquiry', 'AdminCustomerController@addEnquiry');
  Route::post('/customer/enquiry/save', 'AdminCustomerController@saveEnquiry');
  Route::get('/customer/enquiry/edit/{id}', 'AdminCustomerController@editEnquiry');
   Route::get('/customer/enquiry/trash/{id}', 'AdminCustomerController@trashEnquiry');
  Route::get('/customer/add', 'AdminCustomerController@addcustomer')->name('customer-add');
  Route::post('/customer/save', 'AdminCustomerController@addcustomer')->name('customer-save');
  Route::get('/purchase/list', 'AdminCustomerController@purchaseListing')->name('purchase-list');
  Route::get('/purchase/edit/{id}', 'AdminCustomerController@addcustomer')->name('customer-edit');
  // Route::get('/purchase/trash/{id}', 'AdminCustomerController@customersTrash')->name('customer-trash');
  Route::get('/purchase/trash/{id}', 'AdminPropertyController@purchasesTrash')->name('customer-trash');
  Route::post('/ajax/property/details', 'AjaxController@getPropertyDetails')->name('property-details');
  Route::post('/ajax/property/roomDetails', 'AjaxController@getRoomDetail');
  Route::post('/ajax/property/sold-room-detail', 'AjaxController@getSoldRoomDetail');
  Route::post('/ajax/purchase/payment', 'AjaxController@savePayment');
  Route::post('/ajax/purchase/payment-list', 'AjaxController@paymentList');
  // Route::post('/ajax/purchase/payment-defaulter', 'AjaxController@paymentDefaulterList');



  /**
   * FIRM MODULE
   */
  Route::get('/firm/add', 'AdminFirmController@addfirm')->name('firm-add');
  Route::post('/firm/save', 'AdminFirmController@addfirm')->name('firm-save');
  Route::get('/firm/add/{id}', 'AdminFirmController@addfirm')->name('firm-edit');
  Route::get('/firm/trash/{id}', 'AdminFirmController@firmsTrash')->name('firm-trash');

    


  /** * Site Master */
  Route::get('/site-master', 'MasterformsController@addsite')->name('site-master');
  Route::post('/site-master-save', 'MasterformsController@addsite')->name('site-master-save');
  Route::get('/site-master-list', 'MasterformsController@userList')->name('site-master-list');
  Route::get('/site-master-edit/{id}', 'MasterformsController@addsite')->name('site-master-edit');


//----------------------------

//--------------firm module--------------------
  Route::get('/firm/add', 'AdminFirmController@addfirm')->name('firm-add');
  Route::get('/change-password', 'AdminFirmController@changePassword')->name('change-password');
  Route::post('/change-password', 'AdminFirmController@changePassword')->name('change-password');
  Route::post('/firm/save', 'AdminFirmController@addfirm')->name('firm-save');
  Route::get('/firm/add/{id}', 'AdminFirmController@addfirm')->name('firm-edit');
  Route::get('/firm/trash/{id}', 'AdminFirmController@firmsTrash')->name('firm-trash');

  //--------------------------------------------

//-------------------
  Route::get('/construction_site/add', 'AdminConstructionSiteController@addsite')->name('site-add');
  Route::post('/construction_site/save', 'AdminConstructionSiteController@addsite')->name('site-save');
  Route::get('/construction_site/add/{id}', 'AdminConstructionSiteController@addsite')->name('site-edit');
  Route::get('/construction_site/trash/{id}', 'AdminConstructionSiteController@sitesTrash')->name('site-trash');
  //-------------------------

  //-------------------------supplier module----------------  
  Route::get('/supplier/add', 'AdminSupplierController@supplier')->name('supplier-add');
  Route::get('/supplier/add/{id}', 'AdminSupplierController@supplier')->name('supplier-edit');
  Route::get('/supplier/trash/{id}', 'AdminSupplierController@suppliersTrash')->name('supplier-trash');
  Route::post('/supplier/save', 'AdminSupplierController@supplier')->name('supplier-save');
  //-----------------------------------------

  //----------------------stock module--------------------
  Route::get('/stock/add', 'AdminStockController@stockManage')->name('stock-add');
  Route::get('/stock/add/{id}', 'AdminStockController@stockManage')->name('stock-edit');
  Route::get('/stock/trash/{id}', 'AdminStockController@stockTrash')->name('stock-trash');
  Route::post('/stock/save', 'AdminStockController@stockManage')->name('stock-save');
   //----------------------------------------------------


//--------------item module--------------------
  Route::get('/item/category/add', 'AdminItemController@addCategory')->name('category-add');
  Route::post('/item/category/save', 'AdminItemController@addCategory')->name('category-save');
  Route::get('/item/category/add/{id}', 'AdminItemController@addCategory')->name('category-edit');
  Route::get('/item/category/trash/{id}', 'AdminItemController@categoryTrash')->name('category-trash');
  //------------------------------------------------------------------------
  Route::get('/item/subcategory/add', 'AdminItemController@addSubcategory')->name('subcategory-add');
  Route::post('/item/subcategory/save', 'AdminItemController@addSubcategory')->name('subcategory-save');
  Route::get('/item/subcategory/{id}', 'AdminItemController@addSubcategory')->name('subcategory-edit');
  Route::get('/item/subcategory/trash/{id}', 'AdminItemController@subcategoryTrash')->name('subcategory-trash');
  //-----------------------------------------------------------------
  Route::get('/item/item_master/add', 'AdminItemController@addItemMaster')->name('item_master-add');
  Route::post('/item/item_master/save', 'AdminItemController@addItemMaster')->name('item_master-save');
  Route::get('/item/item_master/add/{id}', 'AdminItemController@addItemMaster')->name('item_master-edit');
  Route::get('/item/item_master/trash/{id}', 'AdminItemController@itemMasterTrash')->name('item_master-trash');
 
  
  //--------------------------------------------


  /**
   * Salary Module of Employee
   */
  Route::get('/salary', 'SalaryController@salary')->name('salary-add');
  Route::post('/salary/search', 'SalaryController@salary')->name('salary-search');
  Route::post('/salary/save', 'SalaryController@salary')->name('salary-save');
  Route::get('/salary/list', 'SalaryController@salaryList')->name('salary-list');
  Route::get('/salary/export/{type}', 'SalaryController@salaryList')->name('salary-export');
  Route::get('/report/salary', 'SalaryController@salaryList')->name('report-salary');
  Route::post('/report/salary/search', 'SalaryController@salaryList')->name('report-salary-search');
  Route::get('/report/salary/pdf', 'SalaryController@salaryList')->name('report-salary-pdf');
  

  /**
   * Voucher Module
   */
  Route::get('/voucher-issue', 'MasterformsController@voucherManagement')->name('voucher-issue');
  Route::post('/voucher-save', 'MasterformsController@voucherManagement')->name('voucher-save');
  Route::get('/voucher-list', 'MasterformsController@voucherListing')->name('voucher-list');
  Route::get('/voucher/export/pdf/{pdf}', 'MasterformsController@voucherListing')->name('voucher/export');
  Route::get('/voucher-list', 'MasterformsController@voucherListing')->name('voucher/filter');
  Route::get('/voucher-print/{id}', 'MasterformsController@voucherPrint');


  /**
   * Employee Management
   */
  Route::get('/employee', 'MasterformsController@addUser')->name('employee');
  Route::post('/employee-save', 'MasterformsController@addUser')->name('employee-save');
  Route::get('/employee-list', 'MasterformsController@userList')->name('employee-list'); 
  Route::post('/employee-list', 'MasterformsController@userList')->name('employee-list'); 
  Route::get('/employee-edit/{id}', 'MasterformsController@addUser')->name('employee-edit');
  Route::get('/employee/block/{bool}/{id}', 'MasterformsController@blockUser')->name('user-block');
  Route::get('/employee/trash/{bool}/{id}', 'MasterformsController@trashUser');
  Route::get('/employee/{id}/{view}', 'MasterformsController@addUser')->name('employee-edit');

});