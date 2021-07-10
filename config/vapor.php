<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Redirect "www" To The Root Domain
    |--------------------------------------------------------------------------
    |
    | When this option is enabled, Vapor will redirect requests to the "www"
    | subdomain to the application's root domain. When this option is not
    | enabled, Vapor redirects your root domain to the "www" subdomain.
    |
    */

    'redirect_to_root' => true,

    /*
    |--------------------------------------------------------------------------
    | Redirect robots.txt
    |--------------------------------------------------------------------------
    |
    | When this option is enabled, Vapor will redirect requests for your
    | application's "robots.txt" file to the location of the S3 asset
    | bucket or CloudFront's asset URL instead of serving directly.
    |
    */

    'redirect_robots_txt' => true,

    /*
    |--------------------------------------------------------------------------
    | Servable Assets
    |--------------------------------------------------------------------------
    |
    | Here you can configure list of public assets that should be servable
    | from your application's domain instead of only being servable via
    | the public S3 "asset" bucket or the AWS CloudFront CDN network.
    |
    */

    'serve_assets' => [
        "flutter_service_worker.js",
        "favicon.png",
        "main.dart.js",
        "/",
        "manifest.json",
        "assets/packages/material_design_icons_flutter/lib/fonts/materialdesignicons-webfont.ttf",
        "assets/NOTICES",
        "assets/FontManifest.json",
        "assets/fonts/MaterialIcons-Regular.otf",
        "assets/assets/images/google-icon.png",
        "assets/assets/images/icon.png",
        "assets/assets/images/payment_types/jcb.png",
        "assets/assets/images/payment_types/unionpay.png",
        "assets/assets/images/payment_types/carteblanche.png",
        "assets/assets/images/payment_types/amex.png",
        "assets/assets/images/payment_types/discover.png",
        "assets/assets/images/payment_types/dinerscard.png",
        "assets/assets/images/payment_types/laser.png",
        "assets/assets/images/payment_types/paypal.png",
        "assets/assets/images/payment_types/ach.png",
        "assets/assets/images/payment_types/switch.png",
        "assets/assets/images/payment_types/other.png",
        "assets/assets/images/payment_types/maestro.png",
        "assets/assets/images/payment_types/mastercard.png",
        "assets/assets/images/payment_types/solo.png",
        "assets/assets/images/payment_types/visa.png",
        "assets/AssetManifest.json",
        "version.json",
        "icons/Icon-192.png",
        "icons/Icon-512.png",
        "favicon.ico"
    ],

];
