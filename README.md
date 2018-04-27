FlossWoff2
==========

The goal of this project is to provide an alternative to Google Fonts for fonts
that are licensed under a FLOSS license.

The issue with Google Fonts is they track.

It is highly doubtful that this project will ever completely cover what Google
fonts does. Such is life.

WOFF2 Only
----------

Only WOFF2 fonts will be served by this project. The reason has to do with
space.

At this point, *most* clients that are capable of using web fonts support WOFF2
so it does not make sense to radically increase the space used for the few
that do not.

For a list of clients capable of handling WOFF2:

[https://caniuse.com/#search=woff2](https://caniuse.com/#search=woff2)

At this point in time, the still supported browsers that not handle WOFF2:

* Internet Explorer 11
* Safari on El Capitan or older
* Opera Mini *(does not support any webfonts)*
* UC Browser for Android

With the noted exception of Opera Mini, those clients do support WOFF but it
would more than double the space needed to support them.

Internet Explorer is end of life, it will soon be gone. Safari on supported
versions of iOS and on Mac OS Sierra already support WOFF2.

I would be very surprised if UC Browser for Android does not support it in the
future, but even if it does not, other browsers for Android do support WOFF2
and UC Browser is not a major player and has many security and privacy issues,
it really should not be used.

With respect to Opera Mini, the point of Opera Mini is to be a very low
bandwidth client. Webfont support is counter to that goal.
