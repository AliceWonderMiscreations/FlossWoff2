Font Installation
=================

The fonts that come bundled with this git repository are a very limited set of
very commonly used fonts. They exist primarily for the purpose of allowing a
‘vanilla’ WordPress install with nothing added except for the `AWMFontBuilder`
plugin and the patched twenty-something themes to work with the font server.

Anyone who is running this font server is going to want to add additional some
additional fonts, and probably quite a few.


Step One - Locate the Source for the Font
-----------------------------------------

If at all possible, try to find the original source for the font. The reason is
that the designer very often has the fonts in weights and styles that are not
available at many download locations.

When the font is hosted at Google Fonts, the Google Fonts page describing the
font is often but not always a good place to start in looking for the original
source for the font.

Github is another good place to look. For example, while looking for the
original source for Dosis, the
[Google Fonts Page](https://fonts.google.com/specimen/Dosis) did not link to
the source but a search on Github found it there:
[https://github.com/impallari/Dosis](https://github.com/impallari/Dosis)


Step Two - Compress to WOFF2
----------------------------

The upstream font developer *occassionally* distributes WOFF2 versions of the
font, but usually they only distribute it as either TTF and/or OTF. Either of
those two formats work just fine to create a compressed WOFF2 version.

The utility I use to compress a TTF/OTF font into WOFF2 is called
`woff2_compress` and runs from the command line. It is an open source project,
you can get it from
[https://github.com/google/woff2](https://github.com/google/woff2) or if you
run GNU/Linux, it is quite likely already packaged for your distribution.

Assuming you are in a directory that only contains the TTF fonts you want to
compress to WOFF2, you can use it like this:

    ls *.ttf |while read font; do
    woff2_compress ${font}
    done


Step Three - Create a Directory for the Font
--------------------------------------------

I like to create a two-level deep directory structure. The first has the name
of the font family in ‘UC First CamelCase’ - what that means is the first
letter of every word is capitalized but every other letter is lower case, and
no spaces are used. So for example, Comic Sans MS would become ComicSansMS. Not
that I would ever serve that as a webfont, but...

Within that directory I create a directory that has the version number. I do
this so that when updated to a newer version of the font, I can leave the old
version intact in case I need to switch back.

The WOFF2 fonts that were created in Step Two go into the version directory,
along with two other very important things - the license file that accompanies
that version of the font, and the README that accompanies that version of the
font. It is good to keep those things together.


Step Four - Create a Static CSS File
------------------------------------

The font server uses a static CSS file defining every available style and
weight for the font family which the font server combines with the static CSS
files for other requested families to make a unified CSS file that has
everything the browser needs.

While not strictly required to work, it is good to have a comment block at the
top of the file defining the source for the font, the license for the font, the
README for the font, the version of the font, and any notes you have about the
conversion of the font.

For example, this is the header I used for the Dosis font family:

    /*! Fonts from https://github.com/impallari/Dosis
     *
     * License: https://github.com/impallari/Dosis/blob/master/OFL.txt
     *          (SIL Open Font Licence version 1.1)
     * Readme:  https://github.com/impallari/Dosis/blob/master/README.md
     * Version: v2031b (commit f59c9a42a8b5c7b1b240c37a35cba8f140286b81)
     *
     * Original OTF converted to WOFF2 via https://github.com/google/woff2
     */

I believe it is good to have that kind of transparency available to users who
want to know that information.

Each version of the font needs to have a CSS `@font-face` parameter defining
the family, and here is where something special is done.

An example from the Dosis family:

    @font-face {
      font-family: 'Dosis';
      font-style: normal;
      font-weight: 300;
      src: local('Dosis Light'), local('Dosis-Light'), url(https://webfonts.replaceme.com/Dosis/v2031b/Dosis-v2031b-300Light.woff2) format('woff2');
    }

Since this is the upright variant (opposed to Italic) the `font-style:` is
defined as `normal`.

Withing the `src:` definition, first two `local()` sources are given. Those are
optional, but what they do, if the user happens to already have the specified
font installed the browser will use them instead of downloading the webfont.

I personally know users with slow connections who pay for every byte that they
download, and some of them have downloaded massive collections of Google Fonts
from the library or coffee shop where bandwidth is better just so that they can
have both a faster response time and use less bandwidth when they are surfing
from home with their slow costly connections.

Within the `url()` source, notice the domain is defined as:

    webfonts.replaceme.com

It is important to use that exact string. The `ServeStyle.php` script that
creates the CSS file for the end user will replace that string with the actual
domain name the font server is running from.

To see what the result looks like:

[https://fonts.trippyid.com/css?family=Dosis](https://fonts.trippyid.com/css?family=Dosis)




























