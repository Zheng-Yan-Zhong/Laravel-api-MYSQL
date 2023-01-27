# Laravel
## 建立專案

```bash
composer create-project laravel/laravel backend
```

```bash
cd backend
```

```bash
php artisan serve
```

或是加入環境變數
```bash
composer global require laravel/installer

laravel new backend
```

---

## RESTful API
透過動詞變化達到API可以清楚辨識，並且建立易讀的遊戲規則

| 動詞 | uri |
| -------- | -------- | 
| GET     | api/user/1     |
| POST     | api/user/1    | 
| DELETE     | api/user/1     | 
| PUT     | api/user/1     | 
| PATCH     | api/user/1     | 


## 檔案結構
* [.env](#Env)
* **app**
  * Http
    * [Controllers](#Controllers)
      * Controller.php
      * UserController.php
  * Exceptions
  * Console
  * [Models](#Models)
  * Providers
* [**routes** ](#Routes)
  * [api.php]()
  * web.php
  * channels.php
  * console.php
* **resource** 放置前端(views、css、Vue)
* database
  * [migrations](#migrationsfile)

---

## ENV
Laravel中預設資料庫為**MYSQL**這邊我們只要修改*DBUSERNAME、DBUSERPASSWORD*

![](https://i.imgur.com/qAgvSVR.png)

---


## Models
```php=
php artisan make:model Product --migraction
```
後面添加參數`--migraction`定義資料表

接著我們至`/migractions/product_table.php`

```php
public function up()
    {
      Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('description')->nullable();
        $table->string('name');
        $table->integer('price');
        $table->timestamps();
      });
    }
```

```php
php artisan migrate
```

![](https://i.imgur.com/e1s5PuJ.png)

這時候會問說並不存在laravel這個資料庫，是否建立它? 輸入yes

![](https://i.imgur.com/54Lg4er.png)

接著我們至db就可以看到剛剛我們需要的資料欄位正確的被建立

![](https://i.imgur.com/6B6yazo.png)

接著我們至`api.php`

```php
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Models\Product; //引入Product

Route::get('/product',function() {
    return Product::all(); 
});

Route::get('/test',function() {
    return "Server running";
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

```
由於目前資料庫中沒資料所以回傳空陣列

![](https://i.imgur.com/7ZRLtxj.png)

接著在`api.php`添加product的post方法

```php=
Route::post('/product',function() {
    return Product::create([
        "name"=>"test",
        "description"=>"for test",
        "price"=>0,
    ]);
});
```

![](https://i.imgur.com/xEWi5pY.png)

會發現回報錯誤，要求我們使用fillable變數定義該填入的資料欄位，所以至`models/product.php`
```php=
<?php
class Product extends Model
{
    use HasFactory;
    public $fillable = [
        "name",
        "description",
        "price"
    ];
}
```

![](https://i.imgur.com/tgexHCc.png)


![](https://i.imgur.com/A3Lrekl.png)

我們至剛剛`api.php`中修改post/product
```php
Route::post('/product',function(Request $request) {
    return Product::create([
        "name"=>$request->name,
        "description"=>$request->description,
        "price"=>$request->name,
    ]);
});
```

![](https://i.imgur.com/wKLLKqc.png)

但是，如果有關api的東西都寫在api.php中會造成難以閱讀，於是我們可以使用Controller也就是middleware來幫助我們分離各個應用的api

```php
php artisan make:controller Product
```

![](https://i.imgur.com/emVeROX.png)

至`api.php`中修改product相關路由函式

```php=
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\ProductController; //引入controller

Route::get('/product',[ProductController::class, 'getProduct']);
Route::post('/product',[ProductController::class, 'createProduct']);


Route::get('/test',function() {
    return "Server running";
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
```

接著至`ProductController.php`

```php
<?php

namespace App\Http\Controllers;
use \App\Models\Product; //引入model
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProduct() {
        return Product::all();
    }

    public function createProduct(Request $request) {
        return Product::create([
            "name"=>$request->name,
            "description"=>$request->description,
            "price"=>$request->price,
        ]);
    }
};

```
`createProduct`
![](https://i.imgur.com/pkKf9Pp.png)

`getProduct`
![](https://i.imgur.com/cSY3A5g.png)

