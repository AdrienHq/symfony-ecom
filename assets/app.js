import './styles/app.scss';
import './bootstrap.js';

import {registerReactControllerComponents} from '@symfony/ux-react';

// import $ from 'jquery';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

// assets/app.js
// registerReactControllerComponents(require.context('./react/controllers', true, /\.(j|t)sx?$/));
// import './react/controllers/Recipes.jsx'
// import './react/controllers/Navigation.jsx'
import './react/controllers/Comment.jsx'

import Quill from 'quill';
import 'quill/dist/quill.snow.css';

document.addEventListener('DOMContentLoaded', () => {
    const editors = document.querySelectorAll('.quill-editor');

    const quillInstances = Array.from(editors).map(editor => {
        const quill = new Quill(editor, {
            theme: 'snow'
        });

        const textarea = editor.nextElementSibling;
        quill.on('text-change', function () {
            textarea.value = quill.root.innerHTML;
        });

        return quill;
    });

    document.querySelector('form').onsubmit = function () {
        quillInstances.forEach(quill => {
            const editor = quill.container;
            const textarea = editor.nextElementSibling;
            textarea.value = quill.root.innerHTML;
        });
    };
});

