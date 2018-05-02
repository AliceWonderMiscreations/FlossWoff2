Internet Explorer
=================

When I made the decision to only provide WOFF2 format webfonts, it was a
decision based on disk space.

For wealthy companies, disk space is peanuts. For me it is not. A WOFF font is
roughly 10 to 50% larger than a WOFF2 font depending upon the font.

I _have_ to provide WOFF2 because it is the smallest webfont format and the
vast majority of browsers in use support it. The smaller size of the font file
means both a faster load time for the client as well as less strain on the font
server.

There are only two browsers with major market share that do not support WOFF2

* Internet Explorer 11
* Safari on El Capitan

I honestly was not worried about Internet Explorer. It uses the Trident engine
which Microsoft is no longer developing, and they have been very vocal about
the fact that it is no longer being developed.

I honestly did not expect that roughly 8% of desktop users in the United States
would still be using Internet Explorer today, but oddly, that seems to be the
case.

I queried some people as to why, it seems rather stupid that people would still
be using IE when it has always been such a quirky browser that abused the
standards and now, even Microsoft says to stop using it and use Edge instead.

Well, it seems a lot of web applications were written for Internet Explorer and
actually do not function with standards compliant browsers, even breaking on
Edge.

These web applications are largely built by closed source companies and simply
are not being updated.

As such, Internet Explorer will still probably be continued to be used by those
who need those web applications, which really sucks.

If I had the money the solution would be simple, just add the WOFF version of
webfont. However doing so would reduce the number of fonts I could host by more
than half. I am not willing to do that.

Internet Explorer users will still be able to use your website if you use my
font server, they just will be using system fonts rather than the web fonts.

In some cases they may see the fonts you intend if they have the font installed
on their system, but they will not see the font you intended if they do not.

It would be possible to sniff the user agent in the `AWMFontBuilder` class and
just continue to use Google Fonts if the user agent either reports the Trident
agent or Safari on Mac OS X El Capitan or older, but I really do not want to do
that because it subjects those users to Google tracking.

So I am going to do nothing. Some users will use system fonts instead of
webfonts. As someone who regularly uses system fonts because I normally block
Google Fonts, I can assure you it really is not a big deal, websites still look
fine, they just do not use the fonts the web designer had in mind.

To me it is not a big deal, and that 8% of desktop users is a number that is
going to shrink and continue to shrink, but I thought the proper thing to do is
inform people that the issue does exist.

With respect to Safari on El Capitan and older, well, there have been two major
releases of OS X since El Capitan. It will probably no longer be supported
much longer after the next major release (MacOS 10.14) which is likely coming
this fall.

Even though Internet Explorer is no longer being developed, it still is being
officially supported. So that is why it is important to note that people using
Internet Explorer 11 (or older) will not make use of the webfonts served by
this project.
