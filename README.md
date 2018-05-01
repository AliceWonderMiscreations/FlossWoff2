FlossWoff2
==========

__BEAR WITH ME - BETTER DOCUMENTATION COMING__

The goal of this project is to provide an alternative to Google Fonts for fonts
that are licensed under a FLOSS license.

The issue with Google Fonts is they track.

It is highly doubtful that this project will ever completely cover what Google
fonts does. Such is life.

This project has three components:

1. WOFF2 Font Files
2. CSS server
3. WordPress Plugin

WOFF2 Font Files
----------------

Only WOFF2 webfonts are supported. Nutshell, there are four types of webfonts,
listed below in largest file size to smallest.

1. TrueType
2. EOT
3. WOFF
4. WOFF2

Every current vendor supported browser that has support for TTF and/or EOT font
files also has support for WOFF and often WOFF2. There is no point in consuming
disk space by hosting TrueType and/or EOT versions of the fonts.

With respect to the WOFF format, there are only three browsers that are still
supported by their vendors that support WOFF but do not support WOFF2:

1. Internet Explorer 11
2. Safari on El Capitan and older
3. UC Browser

Internet Explorer 11 is a dead product, it only receives security updates. The
trident engine is no longer being developed.

Safari on all supported version of iOS and on Mac OS Sierra supports WOFF2. El
Capitan (OS X 10.11) will probably be end of life in under two years.

With respect to UC Browser, it is a security and privacy nightmare that no one
should use.

It just does not make sense to more than double the storage space just to
support those browsers.

### Font Families

Only font families available from Google Fonts that have a license allowing
them to be served without a royalty or fee will be part of this project.


CSS Server
----------

This is a PHP script that takes the GET query args and turns them into a CSS
file the client can use to fetch the fonts it needs to render the web page as
intended.

In the spirit of KISS, I do things quite differently than Google does.

### Font Family

The CSS file is constructed based upon the requested font families, but the
CSS script doesn't care what weights or variants are part of the query string,
it just lists them all.

To me that makes more sense. The client will only download the fonts that it is
actually going to use, so it does not hurt to list fonts weights and variants
that it is not going to use.

What it does is simplify the server-side generation, makes it easier for to
cache the generated response, and compensates for mistakes where the web
application has a bug resulting in it not specifying a weight or variant that
it is going to want to use. Sure it makes the CSS file a little bigger, but it
still is a relatively small percentage of the bytes needed for a typical web
page.

### Font Subset

The specified font subset is also ignored. Google’s font server actually also
ignores the subset on browsers that are capable of dealing with Unicode Ranges.

As every browser that handles WOFF2 can also handle Unicode Ranges and this
font server only supports WOFF2, it just does not make sense to even care about
the requested font subset.

Initially, this font server is not even splitting fonts up by range, but is
just serving the entire font. That will change, at some point the fonts will be
broken up in a similar fashion to how Google breaks them up, into smaller fonts
with just the characters in particular a particular range.

I will probably do it a little different, meaning I will probably have the
range for `latin` and `latin-ext` in a single file, `greek` and `greek-ext` in
a single file, etc.

When the extended character set is needed, the base character set is almost
also always needed so to me it makes more sense to reduce the HTTP requests and
just have all the glyphs for the base and extended in the same font.

Also, there will cases where only glyphs from the base set are needed on the
landing page but glyphs from the extended set are needed as the result of an
AJAX request. If the browser already has downloaded a font with glyphs from the
extended set then the text won't initially render with a browser fallback as it
downloads the extended set and then "jump" when the extended font is downloaded
causing the text to re-render.

To me it just does not seem worth it to safe a few kilobytes on some sites, so
unless I see a really good argument for keeping them separate, when glyphs for
a language are split into a base and extended set, I will just use a subset of
the font has both.

That is all in the future anyway, initially the CSS will just declare the
complete font with every glyph the font covers.


WordPress Plugin
----------------

I will detail the plugin here later. For now, just read the
[README.md](wordpress/README.md) file.

