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
* Rename `application/config/application.php.template` to application.php and edit it:
	* Set base_url same as web server base.

### How to use:
* Upload your intended directory structure with picture files to `upload/`
* Visit admin/update url (currently everyone can do that, but it's harmless)
* Verify the gallery shows up as expected.
* Optionally, delete the original pics (which could take up a lot of space)
  from `upload/` - if you want to delete pictures, delete them from `gallery/`

## How to customize:
Coming soon!
