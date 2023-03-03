## (Simple, web-based) Gallery

I created this gallery because all the other galleries seem too complicated.

### What you'll need

* [PHP 7.2+](https://www.php.net/) with Gd
* [Composer 2](https://getcomposer.org/)

### Install

* Grab a copy: `git clone git://github.com/tasuki/gallery.git`
* Install dependencies: `composer install`
* Set up with a web server:
	* Document root to `public/`, redirect nonexistent files to `index.php`.
	* These directories need to be writable by the web server:
		* `public/gallery/`
		* `var/`
* Create `.env.local` to customize, otherwise inherits from `.env`:
	* Set website title: `APP_TITLE="your gallery"`
	* Set license link: `LICENSE_LINK="https://example.com/license"`
	* Set license name: `LICENSE_NAME="Example License"`

### Use

* Upload your intended directory structure with picture files to `upload/`.
* Visit `/admin/update` url (currently everyone can do that, but it's harmless).
* Verify the gallery shows up as expected.
* Optionally, delete the original pics (which could take up a lot of space)
  from `upload/` - if you want to delete the photos from the gallery, delete
  them from `public/gallery/`.

### Upgrading from 2.x

Regenerate thumbnails larger and in webp:

	for i in `fd --type file -I | grep -v "/__"`; do convert $i -resize 600x300 `echo $i | sed 's/\/\([^\/]*\)$/\/__\1/' | sed 's/\.[^.]*$/.webp/'`; done

After everything's working, remove the original thumbnails:

	fd -I | grep "__.*\.jpg" | xargs rm
