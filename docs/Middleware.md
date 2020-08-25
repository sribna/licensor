# Middleware

You can use the `licensor.key.feature` middleware to restrict access to certain areas on your site
depending on the features that are defined for the customer's key plan. This can be useful, for example, when
you add license management functionality to an existing site that has a support forum, download center, etc.
In this case create features "download" and "support", assign them to a plan, then edit your routes:

    Route::group(['middleware' => ['licensor.key.feature:support,download']], function () {
        Route::get('issues','App\Http\Controllers\IssueController@index');
        Route::get('download','App\Http\Controllers\DownloadController@index');
    });

Also, don't forget to add `Sribna\Licensor\Traits\HasKey` trait to `App\User` model.
This trait injects `hasKeyFeature()` method used by the `licensor.key.feature` middleware.

    if(auth()->check() && auth()->user()->hasKeyFeature('download')){
        // Download is allowed!
    }
