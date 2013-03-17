## Additional Assetic Filters

### Based on mrclay/minify

This package adds 2 filters:

    CSSminFilter
    UriPrependFilter

### CSSminFilter

Run a CSS asset through CSSmin.php

### UriPrependFilter

Append a path to all files in a CSS asset. Can either be set with the first argument.

### mrclay/minify

The dev-master from https://github.com/mrclay/minify/ is added as required package. This package includes CSSmin, JSMin (for the default JSMinFilter), and the additional libraries from mrclay/minify
