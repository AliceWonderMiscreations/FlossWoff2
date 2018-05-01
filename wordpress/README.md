WordPress README
================

Place the file `AWMFontBuilder.php` within your “Must Use” plugin directory.

Usually this is `wp-content/mu-plugins` unless you have specifically changed
it. You may have to create the directory, it does not exist by default.

Plugins in that directory do not need to be activated, just by being there they
are loaded and loaded *before* standard plugins and themes.

In your `wp-config.php` file, add the following line:

    //define('WEBFONT_MIRROR', 'fonts.trippyid.com');

Notice that it is commented out. Do not uncomment it until you have received
confirmation that your WordPress host is allowed to use my font mirror service.

If you are running your own Google Font mirror, change `fonts.trippyid.com` to
the hostname of your mirror.

Theme Patching
--------------

The `.diff` files in this directory are patches that should apply to the themes
that match the name of the `.diff` file.

How to patch a theme:

    cp twentyseventeen.diff /path/to/wp-content/themes/twentyseventeen/
    pushd /path/to/wp-content/themes/twentyseventeen/
    patch -p1 < twentyseventeen.diff

You may need to use `sudo` for the `patch` command, depending upon the file
permissions.

If a patch for the specific theme you use does not exist, contact me and I
will see if I can create one for you.

In most cases, you can probably also figure it out by looking at the diff files
already created, it generally is not very hard.