The TrippyId Font Server
========================

TrippyId.com is a domain I originally registered for my
[Groovytar][https://github.com/AliceWonderMiscreations/Groovytar] project.

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

That's all you need, and you have every weight of those fonts available in both Upright (Roman) and Italic variants.

Here is what you get with a similar simple query to Google Fonts:

[https://fonts.googleapis.com/css?family=LibreFranklin|Merriweather|Inconsolata](https://fonts.googleapis.com/css?family=Libre+Franklin|Merriweather|Inconsolata)
