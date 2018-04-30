FlossWoff2
==========

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

Safari on all supported version of iOS and on Mac OS Sierra support WOFF2. El
Capitan (OS X 10.11) will probably be end of life in under two years.

With respect to UC Browser, it is a security and privacy nightmare that no one
should use.

It just does not make sense to more than double the storage space just to
support those browsers.

### Font Families

Only font families available from Google Fonts that have a license allowing
them to be served without a royalty or fee will be part of this project.


















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
