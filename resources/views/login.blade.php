<html>

<head>
    <title>Authenticate</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /*
! tailwindcss v3.1.8 | MIT License | https://tailwindcss.com
*/
        /*
1. Prevent padding and border from affecting element width. (https://github.com/mozdevs/cssremedy/issues/4)
2. Allow adding a border to an element by just adding a border-width. (https://github.com/tailwindcss/tailwindcss/pull/116)
*/

        *,
        ::before,
        ::after {
            box-sizing: border-box;
            /* 1 */
            border-width: 0;
            /* 2 */
            border-style: solid;
            /* 2 */
            border-color: #e5e7eb;
            /* 2 */
        }

        ::before,
        ::after {
            --tw-content: '';
        }

        /*
1. Use a consistent sensible line-height in all browsers.
2. Prevent adjustments of font size after orientation changes in iOS.
3. Use a more readable tab size.
4. Use the user's configured `sans` font-family by default.
*/

        html {
            line-height: 1.5;
            /* 1 */
            -webkit-text-size-adjust: 100%;
            /* 2 */
            -moz-tab-size: 4;
            /* 3 */
            -o-tab-size: 4;
            tab-size: 4;
            /* 3 */
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            /* 4 */
        }

        /*
1. Remove the margin in all browsers.
2. Inherit line-height from `html` so users can set them as a class directly on the `html` element.
*/

        body {
            margin: 0;
            /* 1 */
            line-height: inherit;
            /* 2 */
        }

        /*
1. Add the correct height in Firefox.
2. Correct the inheritance of border color in Firefox. (https://bugzilla.mozilla.org/show_bug.cgi?id=190655)
3. Ensure horizontal rules are visible by default.
*/

        hr {
            height: 0;
            /* 1 */
            color: inherit;
            /* 2 */
            border-top-width: 1px;
            /* 3 */
        }

        /*
Add the correct text decoration in Chrome, Edge, and Safari.
*/

        abbr:where([title]) {
            -webkit-text-decoration: underline dotted;
            text-decoration: underline dotted;
        }

        /*
Remove the default font size and weight for headings.
*/

        h1,
        h2,
        h3,
        h4,
        h5,
        h6 {
            font-size: inherit;
            font-weight: inherit;
        }

        /*
Reset links to optimize for opt-in styling instead of opt-out.
*/

        a {
            color: inherit;
            text-decoration: inherit;
        }

        /*
Add the correct font weight in Edge and Safari.
*/

        b,
        strong {
            font-weight: bolder;
        }

        /*
1. Use the user's configured `mono` font family by default.
2. Correct the odd `em` font sizing in all browsers.
*/

        code,
        kbd,
        samp,
        pre {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            /* 1 */
            font-size: 1em;
            /* 2 */
        }

        /*
Add the correct font size in all browsers.
*/

        small {
            font-size: 80%;
        }

        /*
Prevent `sub` and `sup` elements from affecting the line height in all browsers.
*/

        sub,
        sup {
            font-size: 75%;
            line-height: 0;
            position: relative;
            vertical-align: baseline;
        }

        sub {
            bottom: -0.25em;
        }

        sup {
            top: -0.5em;
        }

        /*
1. Remove text indentation from table contents in Chrome and Safari. (https://bugs.chromium.org/p/chromium/issues/detail?id=999088, https://bugs.webkit.org/show_bug.cgi?id=201297)
2. Correct table border color inheritance in all Chrome and Safari. (https://bugs.chromium.org/p/chromium/issues/detail?id=935729, https://bugs.webkit.org/show_bug.cgi?id=195016)
3. Remove gaps between table borders by default.
*/

        table {
            text-indent: 0;
            /* 1 */
            border-color: inherit;
            /* 2 */
            border-collapse: collapse;
            /* 3 */
        }

        /*
1. Change the font styles in all browsers.
2. Remove the margin in Firefox and Safari.
3. Remove default padding in all browsers.
*/

        button,
        input,
        optgroup,
        select,
        textarea {
            font-family: inherit;
            /* 1 */
            font-size: 100%;
            /* 1 */
            font-weight: inherit;
            /* 1 */
            line-height: inherit;
            /* 1 */
            color: inherit;
            /* 1 */
            margin: 0;
            /* 2 */
            padding: 0;
            /* 3 */
        }

        /*
Remove the inheritance of text transform in Edge and Firefox.
*/

        button,
        select {
            text-transform: none;
        }

        /*
1. Correct the inability to style clickable types in iOS and Safari.
2. Remove default button styles.
*/

        button,
        [type='button'],
        [type='reset'],
        [type='submit'] {
            -webkit-appearance: button;
            /* 1 */
            background-color: transparent;
            /* 2 */
            background-image: none;
            /* 2 */
        }

        /*
Use the modern Firefox focus style for all focusable elements.
*/

        :-moz-focusring {
            outline: auto;
        }

        /*
Remove the additional `:invalid` styles in Firefox. (https://github.com/mozilla/gecko-dev/blob/2f9eacd9d3d995c937b4251a5557d95d494c9be1/layout/style/res/forms.css#L728-L737)
*/

        :-moz-ui-invalid {
            box-shadow: none;
        }

        /*
Add the correct vertical alignment in Chrome and Firefox.
*/

        progress {
            vertical-align: baseline;
        }

        /*
Correct the cursor style of increment and decrement buttons in Safari.
*/

        ::-webkit-inner-spin-button,
        ::-webkit-outer-spin-button {
            height: auto;
        }

        /*
1. Correct the odd appearance in Chrome and Safari.
2. Correct the outline style in Safari.
*/

        [type='search'] {
            -webkit-appearance: textfield;
            /* 1 */
            outline-offset: -2px;
            /* 2 */
        }

        /*
Remove the inner padding in Chrome and Safari on macOS.
*/

        ::-webkit-search-decoration {
            -webkit-appearance: none;
        }

        /*
1. Correct the inability to style clickable types in iOS and Safari.
2. Change font properties to `inherit` in Safari.
*/

        ::-webkit-file-upload-button {
            -webkit-appearance: button;
            /* 1 */
            font: inherit;
            /* 2 */
        }

        /*
Add the correct display in Chrome and Safari.
*/

        summary {
            display: list-item;
        }

        /*
Removes the default spacing and border for appropriate elements.
*/

        blockquote,
        dl,
        dd,
        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        hr,
        figure,
        p,
        pre {
            margin: 0;
        }

        fieldset {
            margin: 0;
            padding: 0;
        }

        legend {
            padding: 0;
        }

        ol,
        ul,
        menu {
            list-style: none;
            margin: 0;
            padding: 0;
        }

        /*
Prevent resizing textareas horizontally by default.
*/

        textarea {
            resize: vertical;
        }

        /*
1. Reset the default placeholder opacity in Firefox. (https://github.com/tailwindlabs/tailwindcss/issues/3300)
2. Set the default placeholder color to the user's configured gray 400 color.
*/

        input::-moz-placeholder,
        textarea::-moz-placeholder {
            opacity: 1;
            /* 1 */
            color: #9ca3af;
            /* 2 */
        }

        input::placeholder,
        textarea::placeholder {
            opacity: 1;
            /* 1 */
            color: #9ca3af;
            /* 2 */
        }

        /*
Set the default cursor for buttons.
*/

        button,
        [role="button"] {
            cursor: pointer;
        }

        /*
Make sure disabled buttons don't get the pointer cursor.
*/
        :disabled {
            cursor: default;
        }

        /*
1. Make replaced elements `display: block` by default. (https://github.com/mozdevs/cssremedy/issues/14)
2. Add `vertical-align: middle` to align replaced elements more sensibly by default. (https://github.com/jensimmons/cssremedy/issues/14#issuecomment-634934210)
   This can trigger a poorly considered lint error in some tools but is included by design.
*/

        img,
        svg,
        video,
        canvas,
        audio,
        iframe,
        embed,
        object {
            display: block;
            /* 1 */
            vertical-align: middle;
            /* 2 */
        }

        /*
Constrain images and videos to the parent width and preserve their intrinsic aspect ratio. (https://github.com/mozdevs/cssremedy/issues/14)
*/

        img,
        video {
            max-width: 100%;
            height: auto;
        }

        *,
        ::before,
        ::after {
            --tw-border-spacing-x: 0;
            --tw-border-spacing-y: 0;
            --tw-translate-x: 0;
            --tw-translate-y: 0;
            --tw-rotate: 0;
            --tw-skew-x: 0;
            --tw-skew-y: 0;
            --tw-scale-x: 1;
            --tw-scale-y: 1;
            --tw-pan-x: ;
            --tw-pan-y: ;
            --tw-pinch-zoom: ;
            --tw-scroll-snap-strictness: proximity;
            --tw-ordinal: ;
            --tw-slashed-zero: ;
            --tw-numeric-figure: ;
            --tw-numeric-spacing: ;
            --tw-numeric-fraction: ;
            --tw-ring-inset: ;
            --tw-ring-offset-width: 0px;
            --tw-ring-offset-color: #fff;
            --tw-ring-color: rgb(59 130 246 / 0.5);
            --tw-ring-offset-shadow: 0 0 #0000;
            --tw-ring-shadow: 0 0 #0000;
            --tw-shadow: 0 0 #0000;
            --tw-shadow-colored: 0 0 #0000;
            --tw-blur: ;
            --tw-brightness: ;
            --tw-contrast: ;
            --tw-grayscale: ;
            --tw-hue-rotate: ;
            --tw-invert: ;
            --tw-saturate: ;
            --tw-sepia: ;
            --tw-drop-shadow: ;
            --tw-backdrop-blur: ;
            --tw-backdrop-brightness: ;
            --tw-backdrop-contrast: ;
            --tw-backdrop-grayscale: ;
            --tw-backdrop-hue-rotate: ;
            --tw-backdrop-invert: ;
            --tw-backdrop-opacity: ;
            --tw-backdrop-saturate: ;
            --tw-backdrop-sepia: ;
        }

        ::-webkit-backdrop {
            --tw-border-spacing-x: 0;
            --tw-border-spacing-y: 0;
            --tw-translate-x: 0;
            --tw-translate-y: 0;
            --tw-rotate: 0;
            --tw-skew-x: 0;
            --tw-skew-y: 0;
            --tw-scale-x: 1;
            --tw-scale-y: 1;
            --tw-pan-x: ;
            --tw-pan-y: ;
            --tw-pinch-zoom: ;
            --tw-scroll-snap-strictness: proximity;
            --tw-ordinal: ;
            --tw-slashed-zero: ;
            --tw-numeric-figure: ;
            --tw-numeric-spacing: ;
            --tw-numeric-fraction: ;
            --tw-ring-inset: ;
            --tw-ring-offset-width: 0px;
            --tw-ring-offset-color: #fff;
            --tw-ring-color: rgb(59 130 246 / 0.5);
            --tw-ring-offset-shadow: 0 0 #0000;
            --tw-ring-shadow: 0 0 #0000;
            --tw-shadow: 0 0 #0000;
            --tw-shadow-colored: 0 0 #0000;
            --tw-blur: ;
            --tw-brightness: ;
            --tw-contrast: ;
            --tw-grayscale: ;
            --tw-hue-rotate: ;
            --tw-invert: ;
            --tw-saturate: ;
            --tw-sepia: ;
            --tw-drop-shadow: ;
            --tw-backdrop-blur: ;
            --tw-backdrop-brightness: ;
            --tw-backdrop-contrast: ;
            --tw-backdrop-grayscale: ;
            --tw-backdrop-hue-rotate: ;
            --tw-backdrop-invert: ;
            --tw-backdrop-opacity: ;
            --tw-backdrop-saturate: ;
            --tw-backdrop-sepia: ;
        }

        ::backdrop {
            --tw-border-spacing-x: 0;
            --tw-border-spacing-y: 0;
            --tw-translate-x: 0;
            --tw-translate-y: 0;
            --tw-rotate: 0;
            --tw-skew-x: 0;
            --tw-skew-y: 0;
            --tw-scale-x: 1;
            --tw-scale-y: 1;
            --tw-pan-x: ;
            --tw-pan-y: ;
            --tw-pinch-zoom: ;
            --tw-scroll-snap-strictness: proximity;
            --tw-ordinal: ;
            --tw-slashed-zero: ;
            --tw-numeric-figure: ;
            --tw-numeric-spacing: ;
            --tw-numeric-fraction: ;
            --tw-ring-inset: ;
            --tw-ring-offset-width: 0px;
            --tw-ring-offset-color: #fff;
            --tw-ring-color: rgb(59 130 246 / 0.5);
            --tw-ring-offset-shadow: 0 0 #0000;
            --tw-ring-shadow: 0 0 #0000;
            --tw-shadow: 0 0 #0000;
            --tw-shadow-colored: 0 0 #0000;
            --tw-blur: ;
            --tw-brightness: ;
            --tw-contrast: ;
            --tw-grayscale: ;
            --tw-hue-rotate: ;
            --tw-invert: ;
            --tw-saturate: ;
            --tw-sepia: ;
            --tw-drop-shadow: ;
            --tw-backdrop-blur: ;
            --tw-backdrop-brightness: ;
            --tw-backdrop-contrast: ;
            --tw-backdrop-grayscale: ;
            --tw-backdrop-hue-rotate: ;
            --tw-backdrop-invert: ;
            --tw-backdrop-opacity: ;
            --tw-backdrop-saturate: ;
            --tw-backdrop-sepia: ;
        }

        .container {
            width: 100%;
        }

        @media (min-width: 640px) {

            .container {
                max-width: 640px;
            }
        }

        @media (min-width: 768px) {

            .container {
                max-width: 768px;
            }
        }

        @media (min-width: 1024px) {

            .container {
                max-width: 1024px;
            }
        }

        @media (min-width: 1280px) {

            .container {
                max-width: 1280px;
            }
        }

        @media (min-width: 1536px) {

            .container {
                max-width: 1536px;
            }
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }

        .pointer-events-none {
            pointer-events: none;
        }

        .fixed {
            position: fixed;
        }

        .absolute {
            position: absolute;
        }

        .relative {
            position: relative;
        }

        .inset-0 {
            top: 0px;
            right: 0px;
            bottom: 0px;
            left: 0px;
        }

        .inset-y-0 {
            top: 0px;
            bottom: 0px;
        }

        .left-0 {
            left: 0px;
        }

        .top-0 {
            top: 0px;
        }

        .right-0 {
            right: 0px;
        }

        .top-3 {
            top: 0.75rem;
        }

        .right-2\.5 {
            right: 0.625rem;
        }

        .right-2 {
            right: 0.5rem;
        }

        .z-10 {
            z-index: 10;
        }

        .z-40 {
            z-index: 40;
        }

        .z-50 {
            z-index: 50;
        }

        .m-3 {
            margin: 0.75rem;
        }

        .mx-auto {
            margin-left: auto;
            margin-right: auto;
        }

        .-mx-3 {
            margin-left: -0.75rem;
            margin-right: -0.75rem;
        }

        .mt-6 {
            margin-top: 1.5rem;
        }

        .mt-8 {
            margin-top: 2rem;
        }

        .mt-1 {
            margin-top: 0.25rem;
        }

        .mb-4 {
            margin-bottom: 1rem;
        }

        .-ml-1 {
            margin-left: -0.25rem;
        }

        .mr-3 {
            margin-right: 0.75rem;
        }

        .mr-2 {
            margin-right: 0.5rem;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .ml-auto {
            margin-left: auto;
        }

        .mb-1 {
            margin-bottom: 0.25rem;
        }

        .mt-0 {
            margin-top: 0px;
        }

        .mb-0 {
            margin-bottom: 0px;
        }

        .block {
            display: block;
        }

        .flex {
            display: flex;
        }

        .inline-flex {
            display: inline-flex;
        }

        .table {
            display: table;
        }

        .grid {
            display: grid;
        }

        .hidden {
            display: none;
        }

        .h-12 {
            height: 3rem;
        }

        .h-5 {
            height: 1.25rem;
        }

        .h-full {
            height: 100%;
        }

        .h-3 {
            height: 0.75rem;
        }

        .h-10 {
            height: 2.5rem;
        }

        .min-h-full {
            min-height: 100%;
        }

        .min-h-\[120px\] {
            min-height: 120px;
        }

        .min-h-\[150px\] {
            min-height: 150px;
        }

        .w-full {
            width: 100%;
        }

        .w-auto {
            width: auto;
        }

        .w-5 {
            width: 1.25rem;
        }

        .w-4 {
            width: 1rem;
        }

        .w-3 {
            width: 0.75rem;
        }

        .w-12 {
            width: 3rem;
        }

        .w-10 {
            width: 2.5rem;
        }

        .max-w-md {
            max-width: 28rem;
        }

        .table-auto {
            table-layout: auto;
        }

        .transform {
            transform: translate(var(--tw-translate-x), var(--tw-translate-y)) rotate(var(--tw-rotate)) skewX(var(--tw-skew-x)) skewY(var(--tw-skew-y)) scaleX(var(--tw-scale-x)) scaleY(var(--tw-scale-y));
        }

        @-webkit-keyframes spin {

            to {
                transform: rotate(360deg);
            }
        }

        @keyframes spin {

            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin {
            -webkit-animation: spin 1s linear infinite;
            animation: spin 1s linear infinite;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .appearance-none {
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
        }

        .grid-cols-3 {
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .flex-col {
            flex-direction: column;
        }

        .flex-wrap {
            flex-wrap: wrap;
        }

        .items-center {
            align-items: center;
        }

        .justify-center {
            justify-content: center;
        }

        .justify-between {
            justify-content: space-between;
        }

        .gap-4 {
            gap: 1rem;
        }

        .space-y-8> :not([hidden])~ :not([hidden]) {
            --tw-space-y-reverse: 0;
            margin-top: calc(2rem * calc(1 - var(--tw-space-y-reverse)));
            margin-bottom: calc(2rem * var(--tw-space-y-reverse));
        }

        .space-y-6> :not([hidden])~ :not([hidden]) {
            --tw-space-y-reverse: 0;
            margin-top: calc(1.5rem * calc(1 - var(--tw-space-y-reverse)));
            margin-bottom: calc(1.5rem * var(--tw-space-y-reverse));
        }

        .overflow-hidden {
            overflow: hidden;
        }

        .overflow-x-auto {
            overflow-x: auto;
        }

        .overflow-y-auto {
            overflow-y: auto;
        }

        .overflow-x-hidden {
            overflow-x: hidden;
        }

        .whitespace-nowrap {
            white-space: nowrap;
        }

        .rounded-md {
            border-radius: 0.375rem;
        }

        .rounded {
            border-radius: 0.25rem;
        }

        .rounded-lg {
            border-radius: 0.5rem;
        }

        .rounded-full {
            border-radius: 9999px;
        }

        .border {
            border-width: 1px;
        }

        .border-b {
            border-bottom-width: 1px;
        }

        .border-gray-300 {
            --tw-border-opacity: 1;
            border-color: rgb(209 213 219 / var(--tw-border-opacity));
        }

        .border-transparent {
            border-color: transparent;
        }

        .border-gray-100 {
            --tw-border-opacity: 1;
            border-color: rgb(243 244 246 / var(--tw-border-opacity));
        }

        .border-gray-200 {
            --tw-border-opacity: 1;
            border-color: rgb(229 231 235 / var(--tw-border-opacity));
        }

        .border-slate-100 {
            --tw-border-opacity: 1;
            border-color: rgb(241 245 249 / var(--tw-border-opacity));
        }

        .border-red-400 {
            --tw-border-opacity: 1;
            border-color: rgb(248 113 113 / var(--tw-border-opacity));
        }

        .border-green-400 {
            --tw-border-opacity: 1;
            border-color: rgb(74 222 128 / var(--tw-border-opacity));
        }

        .border-red-700 {
            --tw-border-opacity: 1;
            border-color: rgb(185 28 28 / var(--tw-border-opacity));
        }

        .bg-green-600 {
            --tw-bg-opacity: 1;
            background-color: rgb(22 163 74 / var(--tw-bg-opacity));
        }

        .bg-white {
            --tw-bg-opacity: 1;
            background-color: rgb(255 255 255 / var(--tw-bg-opacity));
        }

        .bg-red-100 {
            --tw-bg-opacity: 1;
            background-color: rgb(254 226 226 / var(--tw-bg-opacity));
        }

        .bg-green-100 {
            --tw-bg-opacity: 1;
            background-color: rgb(220 252 231 / var(--tw-bg-opacity));
        }

        .bg-blue-700 {
            --tw-bg-opacity: 1;
            background-color: rgb(29 78 216 / var(--tw-bg-opacity));
        }

        .bg-gray-900 {
            --tw-bg-opacity: 1;
            background-color: rgb(17 24 39 / var(--tw-bg-opacity));
        }

        .bg-transparent {
            background-color: transparent;
        }

        .bg-gray-50 {
            --tw-bg-opacity: 1;
            background-color: rgb(249 250 251 / var(--tw-bg-opacity));
        }

        .bg-slate-200 {
            --tw-bg-opacity: 1;
            background-color: rgb(226 232 240 / var(--tw-bg-opacity));
        }

        .bg-opacity-70 {
            --tw-bg-opacity: 0.7;
        }

        .bg-opacity-50 {
            --tw-bg-opacity: 0.5;
        }

        .p-4 {
            padding: 1rem;
        }

        .p-1\.5 {
            padding: 0.375rem;
        }

        .p-1 {
            padding: 0.25rem;
        }

        .p-2\.5 {
            padding: 0.625rem;
        }

        .p-2 {
            padding: 0.5rem;
        }

        .py-12 {
            padding-top: 3rem;
            padding-bottom: 3rem;
        }

        .px-4 {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .px-3 {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .py-2 {
            padding-top: 0.5rem;
            padding-bottom: 0.5rem;
        }

        .py-4 {
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .px-1 {
            padding-left: 0.25rem;
            padding-right: 0.25rem;
        }

        .px-6 {
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        .py-3 {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .py-6 {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }

        .px-5 {
            padding-left: 1.25rem;
            padding-right: 1.25rem;
        }

        .py-2\.5 {
            padding-top: 0.625rem;
            padding-bottom: 0.625rem;
        }

        .px-2 {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .py-1 {
            padding-top: 0.25rem;
            padding-bottom: 0.25rem;
        }

        .pl-3 {
            padding-left: 0.75rem;
        }

        .pt-4 {
            padding-top: 1rem;
        }

        .pb-4 {
            padding-bottom: 1rem;
        }

        .pt-0 {
            padding-top: 0px;
        }

        .pb-3 {
            padding-bottom: 0.75rem;
        }

        .pl-6 {
            padding-left: 1.5rem;
        }

        .pl-2 {
            padding-left: 0.5rem;
        }

        .text-left {
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-xl {
            font-size: 1.25rem;
            line-height: 1.75rem;
        }

        .text-xs {
            font-size: 0.75rem;
            line-height: 1rem;
        }

        .text-sm {
            font-size: 0.875rem;
            line-height: 1.25rem;
        }

        .text-lg {
            font-size: 1.125rem;
            line-height: 1.75rem;
        }

        .font-semibold {
            font-weight: 600;
        }

        .font-medium {
            font-weight: 500;
        }

        .font-bold {
            font-weight: 700;
        }

        .uppercase {
            text-transform: uppercase;
        }

        .lowercase {
            text-transform: lowercase;
        }

        .capitalize {
            text-transform: capitalize;
        }

        .leading-tight {
            line-height: 1.25;
        }

        .text-gray-900 {
            --tw-text-opacity: 1;
            color: rgb(17 24 39 / var(--tw-text-opacity));
        }

        .text-gray-500 {
            --tw-text-opacity: 1;
            color: rgb(107 114 128 / var(--tw-text-opacity));
        }

        .text-white {
            --tw-text-opacity: 1;
            color: rgb(255 255 255 / var(--tw-text-opacity));
        }

        .text-green-500 {
            --tw-text-opacity: 1;
            color: rgb(34 197 94 / var(--tw-text-opacity));
        }

        .text-blue-700 {
            --tw-text-opacity: 1;
            color: rgb(29 78 216 / var(--tw-text-opacity));
        }

        .text-slate-400 {
            --tw-text-opacity: 1;
            color: rgb(148 163 184 / var(--tw-text-opacity));
        }

        .text-blue-600 {
            --tw-text-opacity: 1;
            color: rgb(37 99 235 / var(--tw-text-opacity));
        }

        .text-red-700 {
            --tw-text-opacity: 1;
            color: rgb(185 28 28 / var(--tw-text-opacity));
        }

        .text-green-700 {
            --tw-text-opacity: 1;
            color: rgb(21 128 61 / var(--tw-text-opacity));
        }

        .text-gray-700 {
            --tw-text-opacity: 1;
            color: rgb(55 65 81 / var(--tw-text-opacity));
        }

        .text-gray-400 {
            --tw-text-opacity: 1;
            color: rgb(156 163 175 / var(--tw-text-opacity));
        }

        .underline {
            -webkit-text-decoration-line: underline;
            text-decoration-line: underline;
        }

        .placeholder-gray-500::-moz-placeholder {
            --tw-placeholder-opacity: 1;
            color: rgb(107 114 128 / var(--tw-placeholder-opacity));
        }

        .placeholder-gray-500::placeholder {
            --tw-placeholder-opacity: 1;
            color: rgb(107 114 128 / var(--tw-placeholder-opacity));
        }

        .opacity-50 {
            opacity: 0.5;
        }

        .opacity-25 {
            opacity: 0.25;
        }

        .opacity-75 {
            opacity: 0.75;
        }

        .opacity-30 {
            opacity: 0.3;
        }

        .shadow-sm {
            --tw-shadow: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --tw-shadow-colored: 0 1px 2px 0 var(--tw-shadow-color);
            box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
        }

        .shadow {
            --tw-shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --tw-shadow-colored: 0 1px 3px 0 var(--tw-shadow-color), 0 1px 2px -1px var(--tw-shadow-color);
            box-shadow: var(--tw-ring-offset-shadow, 0 0 #0000), var(--tw-ring-shadow, 0 0 #0000), var(--tw-shadow);
        }

        .filter {
            filter: var(--tw-blur) var(--tw-brightness) var(--tw-contrast) var(--tw-grayscale) var(--tw-hue-rotate) var(--tw-invert) var(--tw-saturate) var(--tw-sepia) var(--tw-drop-shadow);
        }

        .hover\:bg-green-700:hover {
            --tw-bg-opacity: 1;
            background-color: rgb(21 128 61 / var(--tw-bg-opacity));
        }

        .hover\:bg-blue-800:hover {
            --tw-bg-opacity: 1;
            background-color: rgb(30 64 175 / var(--tw-bg-opacity));
        }

        .hover\:bg-red-600:hover {
            --tw-bg-opacity: 1;
            background-color: rgb(220 38 38 / var(--tw-bg-opacity));
        }

        .hover\:bg-gray-200:hover {
            --tw-bg-opacity: 1;
            background-color: rgb(229 231 235 / var(--tw-bg-opacity));
        }

        .hover\:text-white:hover {
            --tw-text-opacity: 1;
            color: rgb(255 255 255 / var(--tw-text-opacity));
        }

        .hover\:text-gray-900:hover {
            --tw-text-opacity: 1;
            color: rgb(17 24 39 / var(--tw-text-opacity));
        }

        .focus\:z-10:focus {
            z-index: 10;
        }

        .focus\:border-green-500:focus {
            --tw-border-opacity: 1;
            border-color: rgb(34 197 94 / var(--tw-border-opacity));
        }

        .focus\:border-blue-500:focus {
            --tw-border-opacity: 1;
            border-color: rgb(59 130 246 / var(--tw-border-opacity));
        }

        .focus\:outline-none:focus {
            outline: 2px solid transparent;
            outline-offset: 2px;
        }

        .focus\:ring-2:focus {
            --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
            --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);
            box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
        }

        .focus\:ring-4:focus {
            --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
            --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(4px + var(--tw-ring-offset-width)) var(--tw-ring-color);
            box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
        }

        .focus\:ring-green-500:focus {
            --tw-ring-opacity: 1;
            --tw-ring-color: rgb(34 197 94 / var(--tw-ring-opacity));
        }

        .focus\:ring-blue-300:focus {
            --tw-ring-opacity: 1;
            --tw-ring-color: rgb(147 197 253 / var(--tw-ring-opacity));
        }

        .focus\:ring-red-300:focus {
            --tw-ring-opacity: 1;
            --tw-ring-color: rgb(252 165 165 / var(--tw-ring-opacity));
        }

        .focus\:ring-blue-500:focus {
            --tw-ring-opacity: 1;
            --tw-ring-color: rgb(59 130 246 / var(--tw-ring-opacity));
        }

        .focus\:ring-offset-2:focus {
            --tw-ring-offset-width: 2px;
        }

        .group:hover .group-hover\:text-green-400 {
            --tw-text-opacity: 1;
            color: rgb(74 222 128 / var(--tw-text-opacity));
        }

        @media (prefers-color-scheme: dark) {

            .dark\:bg-blue-600 {
                --tw-bg-opacity: 1;
                background-color: rgb(37 99 235 / var(--tw-bg-opacity));
            }

            .dark\:bg-opacity-80 {
                --tw-bg-opacity: 0.8;
            }

            .dark\:text-gray-400 {
                --tw-text-opacity: 1;
                color: rgb(156 163 175 / var(--tw-text-opacity));
            }

            .dark\:placeholder-gray-400::-moz-placeholder {
                --tw-placeholder-opacity: 1;
                color: rgb(156 163 175 / var(--tw-placeholder-opacity));
            }

            .dark\:placeholder-gray-400::placeholder {
                --tw-placeholder-opacity: 1;
                color: rgb(156 163 175 / var(--tw-placeholder-opacity));
            }

            .dark\:hover\:bg-blue-700:hover {
                --tw-bg-opacity: 1;
                background-color: rgb(29 78 216 / var(--tw-bg-opacity));
            }

            .dark\:focus\:ring-blue-800:focus {
                --tw-ring-opacity: 1;
                --tw-ring-color: rgb(30 64 175 / var(--tw-ring-opacity));
            }
        }

        @media (min-width: 640px) {

            .sm\:inline {
                display: inline;
            }

            .sm\:px-6 {
                padding-left: 1.5rem;
                padding-right: 1.5rem;
            }

            .sm\:text-sm {
                font-size: 0.875rem;
                line-height: 1.25rem;
            }
        }

        @media (min-width: 768px) {

            .md\:inset-0 {
                top: 0px;
                right: 0px;
                bottom: 0px;
                left: 0px;
            }

            .md\:h-full {
                height: 100%;
            }

            .md\:h-auto {
                height: auto;
            }

            .md\:w-1\/2 {
                width: 50%;
            }
        }

        @media (min-width: 1024px) {

            .lg\:px-8 {
                padding-left: 2rem;
                padding-right: 2rem;
            }
        }
    </style>
</head>

<body>
    <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <img class="mx-auto h-12 w-auto"
                    src="data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 25 28'%3e%3cpath fill='%2395BF47' fill-rule='evenodd' d='M17.836 27.059l-.062-23.736c-.16-.16-.472-.112-.594-.076l-.813.252a5.675 5.675 0 00-.39-.957c-.576-1.1-1.42-1.682-2.44-1.683h-.003c-.068 0-.136.006-.204.012h-.008a2.234 2.234 0 00-.092-.105C12.786.29 12.216.059 11.533.079c-1.318.038-2.63.99-3.693 2.679-.75 1.19-1.318 2.683-1.48 3.84L3.767 7.4c-.764.24-.788.263-.888.982C2.803 8.928.806 24.377.806 24.377l16.743 2.895.287-.213zM12.35 1.163a1.347 1.347 0 00-.792-.208c-2.033.06-3.807 3.235-4.26 5.352l1.949-.604.347-.107c.255-1.344.896-2.738 1.733-3.636a3.821 3.821 0 011.023-.797zm-1.793 4.135l2.796-.866c.009-.728-.07-1.805-.435-2.565-.388.16-.715.44-.95.691-.628.675-1.14 1.705-1.41 2.74zM14.23 4.16l1.299-.403c-.208-.674-.7-1.805-1.7-1.994.311.802.391 1.73.4 2.397z' clip-rule='evenodd'/%3e%3cpath fill='%235E8E3E' d='M21.587 5.088c-.099-.008-2.035-.037-2.035-.037s-1.619-1.573-1.778-1.733a.399.399 0 00-.225-.103v24.053l7.256-1.804S21.844 5.447 21.825 5.31a.263.263 0 00-.238-.222z'/%3e%3cpath fill='white' d='M13.528 8.824l-.843 3.153s-.94-.429-2.054-.358c-1.635.103-1.652 1.134-1.636 1.392.09 1.41 3.799 1.718 4.008 5.021.163 2.599-1.379 4.376-3.601 4.516-2.667.169-4.135-1.405-4.135-1.405l.565-2.404s1.478 1.115 2.66 1.04c.773-.048 1.05-.677 1.021-1.121-.116-1.84-3.137-1.731-3.328-4.754-.16-2.544 1.51-5.12 5.196-5.353 1.42-.09 2.147.273 2.147.273'/%3e%3c/svg%3e"
                    alt="Workflow">
                <h2 class="mt-6 text-center text-xl font-semibold text-gray-900">Authenticate your store with
                    application</h2>
            </div>
            <form class="mt-8 space-y-6" action="/auth/begin" method="GET">
                <div>
                    <div class="rounded-md shadow-sm">
                        @if ( session('invalid_address', false))
                            <p style="color:#FF0000; text-align: center;"> <b>"Invalid shop address"</b></p>
                        @endif
                        <label for="shop" class="sr-only">Shop address</label>
                        <input id="shop" name="shop" type="text" autocomplete="shop" pattern=".*.myshopify.com.*" title="Enter correct store url form (yourstore.myshopify.com)" required
                            class="appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500 focus:z-10 sm:text-sm"
                            placeholder="Store address">
                    </div>
                    <p class="text-xs mt-1 text-gray-500">Ex: yourstore.myshopify.com</p>
                </div>

                <div>
                    <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <svg class="h-5 w-5 text-green-500 group-hover:text-green-400"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                aria-hidden="true">
                                <path fill-rule="evenodd"
                                    d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                        Submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>