## (Simple, web-based) Gallery

I created this gallery because all the other galleries seem way too complicated.

### What you'll need:
* PHP 7.2+ with ImageMagick
* Composer
* Apache (or other web server)

### How to install:
* Grab a copy:
	* clone the repo: `git clone git://github.com/tasuk/gallery.git`
* Set up with a web server:
	* Document root to `public/`, show all existing files, redirect nonexistent
	  to `index.php`.
	* These directories need to be writable by the server:
		* `public/gallery/`
		* `var/`
* Create `.env.local` to set the title: `APP_TITLE="your gallery"`

### How to use:
* Upload your intended directory structure with picture files to `upload/`
* Visit admin/update url (currently everyone can do that, but it's harmless)
* Verify the gallery shows up as expected.
* Optionally, delete the original pics (which could take up a lot of space)
  from `upload/` - if you want to delete the photos from the gallery, delete
  them from `gallery/`
