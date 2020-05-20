# Laravel blog
Beautiful laravel backend blog system, including migrations, models, and easy writeable featured methods.

## Installation

1. You can install the package via composer command:
	``` bash
	composer require ngodink/laravel-blog
	```
2. The service provider will **automatically get registered**. Or you may manually add the service provider in your `config/app.php` file:

   ``` php
   'providers' => [
      	// ...
	   Ngodink\Blog\BlogServiceProvider::class,
   ];
   ```
3. Run the migrations via artisan command:
   ``` bash
   php artisan migrate
   ```
4. Add the necessary trait to your User model:
   ```php
   use Ngodink\Blog\Traits\UserTrait as BlogTrait;

   class User extends Authenticatable {
	   use BlogTrait;
	   // ...
   }
   ```


## Documentation
You may modify or add something with extends to this base model. Example with create new `App\BlogPost.php` with this configuration:
```php
namespace App;

use Ngodink\Blog\Models\BlogPost as Model;

class BlogPost extends Model {
	//
}
```
---------
### BlogPost
Default class is  ```Ngodink\Blog\Models\BlogPost```  

> This belongs to ```author```  
> This one-to-many to ```comments```, ```tags```, ```metas```  
> This many-to-many to ```categories```

Find post by slug:
``` php
// Return specified post by slug
$post = BlogPost::findBySlug('hello-world-post');
```
Model scopes:
``` php
// Where published_at is not null
BlogPost::published()->get();

// Where published_at is null
BlogPost::unpublished()->get();

// Where published_at is more than now
BlogPost::scheduled()->get();

// Where author_id is (in) users.id
BlogPost::authoredBy(1)->get();
BlogPost::authoredBy([1, 5])->get();

// Where tag is
BlogPost::whereTag('laravel')->get();

// Where liked by (in) users.id
BlogPost::whereLikedBy(1)->get();
BlogPost::whereLikedBy([2, 4, 5])->get();

// Where category is (in) users.id
BlogPost::whereCategoryIn(1)->get();
BlogPost::whereCategoryIn([2, 4, 5])->get();
```
Post metas:
``` php
$post = Post::find(1);

// Return all metas of current post
$post->getMetas();

// Return current meta content where key is
$post->getMeta('seo-title');

// Set current meta content where key is (using updateOrCreate)
$post->setMeta('seo-title', 'Hello world post!');
```
---------
### BlogCatagory
Default class is  ```Ngodink\Blog\Models\BlogCategory```  

> This many-to-many to ```posts```

Find category by slug:
``` php
// Return specified category by slug
$category = BlogCategory::findBySlug('updates');
```
<!-- ## Security -->
<!-- If you discover any security related issues, please email ngodink@gmail.com instead of using the issue tracker. -->

## Credits
- [Rasyid Ridho](https://github.com/ngodink)

## License
MIT. Please see the [license file](LICENSE.md) for more information.
