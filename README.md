## (Simple, web-based) Gallery

I created this gallery because all the other galleries seem way too complicated.

### What you'll need:
* PHP 5.2+ (with gd or imagemagick)
* Apache (or other web server)

### How to install:
* Grab a copy:
	* clone the repo: `git clone git://github.com/tasuk/gallery.git`
	* update submodules: `git submodule init && git submodule update`
* Set up with web server; instructions for Apache:
	* Copy .htaccess.template to .htaccess
	* Edit .htaccess and change RewriteBase if necessary.
	* These directories need to be writable by the server:
		* `gallery/`
		* `application/cache/`
		* `application/logs/`
* Rename `application/config/application.php.template` to application.php and
  edit it:
	* Set base_url same as web server base.

### How to use:
* Upload your intended directory structure with picture files to `upload/`
* Visit admin/update url (currently everyone can do that, but it's harmless)
* Verify the gallery shows up as expected.
* Optionally, delete the original pics (which could take up a lot of space)
  from `upload/` - if you want to delete pictures, delete them from `gallery/`

### How to customize:
Gallery uses Kohana's [cascading filesystem] with a series of modules. These are
configured in the above mentioned application.php config. The default is the
gallery_v1 style, which is loaded like so:

	'templates' => array(
		'gallery_v1',
	),

This is the original style I used to use in my gallery till late 2011.

If you'd like to align the thumbnails using [JQuery Masonry], then use the
following:

	'templates' => array(
		'gallery_masonry',
		'gallery_v1',
	),

Apart from aligning images with masonry, the gallery_masonry module offers a few
extra features, such as linking to the page with current image open.

It's likely you'll want to make some customizations, eg. set your own title. In
that case, please create your own module. Let's call it my_style:

	'templates' => array(
		'my_style',
		'gallery_masonry',
		'gallery_v1',
	),

Copy `modules/gallery_v1/messages/global.php` to
`modules/my_style/messages/global.php` and perform your changes there. If you
take versioning seriously, you should make the `modules/mystyle/` directory
a submodule of the original repo. Browse through the `modules/gallery_v1/` and
`modules/gallery_masonry/` directories to see what can be customized.

If you have any questions/remarks, feel free to contact me or create an issue on
github!

[cascading filesystem]: http://kohanaframework.org/3.2/guide/kohana/files
[JQuery Masonry]: http://masonry.desandro.com/
