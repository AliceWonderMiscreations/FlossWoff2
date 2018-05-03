The TrippyId Font Server
========================

Introduction
------------

TrippyId.com is a domain I originally registered for my
[Groovytar](https://github.com/AliceWonderMiscreations/Groovytar) project.

As that domain will never ever use cookies, it makes a good domain to use for a
trackerless font server as well.

__Nutshell:__ Fonts.TrippyId.com is a trackerless font server that you can use
instead of Fonts.Google.com so that your website can have beautiful web design
with gorgeous fonts that match *without* selling out your visitors to Google
(or other) trackers.

I will ask that you help pay for the bandwidth, but that is all I ask.

I am extremely poor, so if you have the means, I would *appreciate*
a financial tip of appreciation for my code and effort, but I do not require
it.

If you do not wish to pay me for bandwidth but still wish to protect your users
from Google tracking via their font server, this project is open source under
the very liberal [MIT License](https://opensource.org/licenses/MIT) so feel
free to run your own font server using the code here. Instructions are in this
directory.

If you are poor like me and can not afford to pay me for the bandwidth but
still wish to protect your users from Google tracking via their font server,
let me know and we can work something out, like maybe a link or something.

For those with the means, $60 will get you a year of bandwidth for one domain.
That is only $5.00 a month, which is about the cheapest you can get a VM for.

For multiple domains, please contact me with how many.


Simpler API
-----------

The TrippyId font server is compatible with the API used with Google Fonts, but
you do not have to make your `GET` parameters that complex.

Simply specify a list of font families delimited by a `|` and all variants of
those fonts will be available for your web design. For example:

[https://fonts.trippyid.com/css?family=LibreFranklin|Merriweather|Inconsolata](https://fonts.trippyid.com/css?family=Libre+Franklin|Merriweather|Inconsolata)

That's all you need, and you have every weight of those fonts available in both
Upright (Roman) and Italic variants.

Here is what you get with a similar simple query to Google Fonts:

[https://fonts.googleapis.com/css?family=LibreFranklin|Merriweather|Inconsolata](https://fonts.googleapis.com/css?family=Libre+Franklin|Merriweather|Inconsolata)

Notice Google only makes the fonts available in the 400 (Regular) weight, even
though they have far more available. To use additional weights with Google
Fonts you have to explicitly tell it what font weights you want, and whether
or not you want that in italics. That makes for some very long very complex
query strings within your `<link />` tag for anything but the most basic uses.


Additional Weights
------------------

In many cases, a font family has a very decent selection of available font
weights, but Google chooses to only make a few of them (typically 400 and 700)
available through Google Fonts. I do concede that 400 and 700 (Regular and Bold
respectively) are the most commonly used weights, but there often are very
valid reasons for a webmaster to choose a different weight.

When the font family has those additional weights, Google often makes them
available but in many cases they simply choose not to.

From my perspective, if the weight exists, why not let the web designer decide
if they want to make use of them?


Additional Width Variants
-------------------------

In several cases, a font designer will have a font available in several
different width variants (e.g. condensed or semi-condensed). Again these quite
often are available from Google Fonts but that is not always the case.

I prefer to make them available when they exist, condensed fonts can be very
beneficial to web design.


Additional Font Families
------------------------

Google Fonts has far more font families available than are at
Fonts.TrippyId.com, and it will probably remain that way forever. However when
a webmaster wants a font that can be served without a commercial license, it is
not always added to Google Fonts. They only add fonts they believe are
beneficial to them.

My philosophy is a bit different.

If a web master that uses TrippyId.com has found a font they have use for and
it can be served by TrippyId.com without the purchase of a commercial license,
I will quite likely add it simply because it is of benefit to one of my users.


No Tracking Cookies
-------------------

This is the big one for me. No tracking cookies.

Tracking cookies are very problematic for several reasons. First of all, they
are an invasion of privacy. Not just an invasion of privacy, but a
*non-consensual* invasion of privacy.

Most users are very ignorant as to how they work, and are often not even aware
of what trackers on a website they are visiting.

Almost all users (myself included) are completely unaware of the scope of the
profiles that are built about them, and who those profiles are shared with and
sold to.


WordPress Integration
=====================

If you use WordPress, I have made it easy to switch to Fonts.TrippyId.com as
your webfont server in a backwards compatible way.

The main documentation for this is in the
[WordPress](https://github.com/AliceWonderMiscreations/FlossWoff2/tree/master/wordpress)
directory.

The plugin `AWMFontBuilder.php` is a PHP class that provides some functionality
WordPress really should have in core but doesn't.

That plugin is just a class of methods, by itself it does not do anything.

It makes it easy for themes and plugins to make use of webfonts in a Google
Fonts API compatible way, while at the same time allowing the blog
administrator to define the mirror to use for the fonts.

With that plugin installed, themes can be patched to make use of the class so
that Google Fonts is still used by default, but adding a single line to the
`wp-config.ini` file results in the request for the webfont CSS file to be
requested from an alternative to Google Fonts, such as Fonts.TrippyId.com (or a
different implementation of this font server)

Themes and plugins that currently use Google Fonts will need to be patched to
use that class, but that actually is not very difficult. I will add patches to
various themes as I try them myself.


Ready to Try or Buy ???
=======================

If you would like to use Fonts.TrippyId.com, please send an e-mail to
paypal@domblogger.net expressing your interest. When we reach an agreement I
will send you an invoice through PayPal.

If you would like to try Fonts.TrippyId.com before committing, I can add your
hostname(s) to the authorized list for a month of testing before you decide if
you want to commit long term.

If you use WordPress and your theme is not a theme I have already created a
patch for, just let me know what theme you use. If it is a theme that I have
legal access to, I will try to create a patch for you that will let your theme
make use of Fonts.TrippyId.com.


Advantages of Google Fonts over TrippyId
========================================

I believe in very brutal honesty. Sometimes I wonder if that is part of why I
am not financially successful. But it is the right thing to do.

There are some distinctive advantages Google Fonts has.


Server Stability
----------------

Google has tons of money and tons of servers, they own large portions of the
Internet backbone. Their fonts servers are fast and always will be fast.

If you use Fonts.TrippyId.com for fonts, some of your users will take a hit on
page load performance.

With fonts, it isn't the end of the world. The page still renders quickly, just
using system fonts until the browser finishes downloading the font that it
actually needs.

Once the user has downloaded the font, it is cached for some time, but some
users may notice initial page jumpiness as the fonts take slightly longer to
download, especially users that are geographically far from the closest server.

Google also has staff 24/7 to deal with unexpected outages. Me, I have epilepsy
and have seizures if I do not get enough sleep.

I do have scripts running on the servers that restart things if the server
crashes, which never happens, but I can not have the kind of stability Google
has.


Font Selection
--------------

Google has a very large catalog of fonts that are instantly available. I can
certainly add any font they have that is needed, but adding a font is never an
instant thing.

Updates to fonts are also not going to be available as quickly. For the vast
majority of users, that does not ever really matter. But when it does matter,
it matters.


Language Optimization
---------------------

Google splits fonts up into smaller subsets of the font that cover specific
Unicode ranges, so that the browser can often download less by only downloading
the specific language subset font it actually needs.

This is something I plan to add but honestly I do not know how long it will
take.

I do not want to add it just because Google does it, but rather, because it is
conceptually the right way to do things and I like doing things conceptually
the right way.

In some respects Google over-optimizes the splitting of fonts. For example,
they have both latin and latin-ext subsets, greek and greek-ext subsets,
cryllic and cryllic-ext subsets.

I would combine the extended with the base in those cases, that makes more
sense to me. One could argue the split is beneficial when characters from the
extended set are not needed, but most of the time the fonts will be served from
browser cache anyway, so it seems like some of their splitting is optimizing
for the sake of optimizing without actual real world benefit.

It is better though than what I currently have, which is fonts that are not
split at all.


Web Designer Friendliness
-------------------------

It's easy to sample fonts at [https://fonts.google.com] and find one that meets
your needs.

I do not have that, and probably will not. That would require a design team to
create, and well, since I am fundamentally opposed to tracking people as a
means of monetizing the Internet, the funding for a design team to make
something like that just is not going to happen.



























































