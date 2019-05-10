Mimeo Bundle
============
Mimeo: copy (static) assets from NPM packages into your symfony project.

The name is inspired by the short name of a [mimeograph](https://en.wikipedia.org/wiki/Mimeograph).


Integration of Installation Paths
---------------------------------

The symfony bundle can choose where the mimeo assets should be installed to. If the packages use SCSS files that reference these assets relatively, the installation path must be adapted.

The npm package, as well as the project that is using these assets, are supposed to declare (Symfony) respectively use (SCSS) a global variable called **`$mimeo-install-path`**.

**The path must *not* end with a `/`.**

In the npm package SCSS this can look like this:

```scss
$mimeo-install-path: ".." !default;

// .. later ..

a {
    background-image: url("#{$mimeo-install-path}/install-dir/img/example.jpg");
}
```

And in your project's SCSS like this:
```scss
$mimeo-install-path: "../mimeo";
@import "@becklyn/some-package";
```

Note the `install-dir`: the npm package knows the target directory where it will be installed to (as it defines it in the `mimeo` mapping in their `package.json`), so this must be used here as well.
