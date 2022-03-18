# [bootstrap-datepicker-thai](http://jojosati.github.com/bootstrap-datepicker-thai)

Extend eternicode's [bootstrap-datepicker](http://eternicode.github.com/bootstrap-datepicker/) with thai year support.

See [demo](http://jojosati.github.com/bootstrap-datepicker-thai/demo).

## Thai extension

Adding thai-year display/input features base on master branch by eternicode.

[Thai-year](http://en.wikipedia.org/wiki/Thai_solar_calendar) 
uses Buddhist-Era (B.E.) that is 543 years greater than Christian-Era (C.E.).

#### Smart year input detection

Determine year value for both era during user input and trying to convert to proper era automatically.

    7/10/98 -> 7/10/1998(C.E.) or 7/10/2541(B.E.)
    7/10/12 -> 7/10/2012(C.E.) or 7/10/2555(B.E.)
    7/10/55 -> 7/10/2012(C.E.) or 7/10/2555(B.E.)
    7/10/1998 -> 7/10/1998(C.E.) or 7/10/2541(B.E.)
    7/10/2012 -> 7/10/2012(C.E.) or 7/10/2555(B.E.)
    7/10/2555 -> 7/10/2012(C.E.) or 7/10/2555(B.E.)

#### Language option for thai extension

The language file locales/bootstrap-datepicker.th.js support default language 'th' and some more variants.

    th - thai language / C.E.
    th-th - thai language / B.E.
    en-th - eng language / B.E.
    en-en.th - eng language / C.E. (same as 'en' with smart-year input detection)

#### Using with thai extension

Thai extension requires thai language file and the extension file.

    <script type="text/javascript" src="js/bootstrap-datepicker.js"></script>
    <!-- thai extension -->
    <script type="text/javascript" src="js/bootstrap-datepicker-thai.js"></script>
    <script type="text/javascript" src="js/locales/bootstrap-datepicker.th.js"></script>
######
    $('.datepicker').datepicker({language:'th-th',format:'dd/mm/yyyy'})
    
## Other features

Please refer to eternicode's [bootstrap-datepicker](http://eternicode.github.com/bootstrap-datepicker/).
