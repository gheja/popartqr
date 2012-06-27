popartqr
========

A PHP based colorful popart QR code generator powered by [phpqrcode](https://github.com/t0k4rt/phpqrcode).

Based on phpqrcode
==================

The code generation is handled over to phpqrcode.php from [phpqrcode](https://github.com/t0k4rt/phpqrcode).

The version included here is from 13th February 2012, commit d7d79e9eb8c0630d846fbf2f85445fb34583fbe3.

I have made only one modification on line 123, changed the QR_FIND_FROM_RANDOM to false to prevent the randomization in generated codes, so the input text will always generate the exact same QR code.
