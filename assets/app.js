import './styles/app.scss';
import './bootstrap.js';

require('bootstrap');

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

    function imageHandler() {
        const input = document.createElement('input');
        input.setAttribute('type', 'file');
        input.setAttribute('accept', 'image/*');
        input.click();

        input.onchange = () => {
            const file = input.files[0];
            if (file) {
                const formData = new FormData();
                formData.append('file', file);

                // Use the correct upload URL
                const uploadUrl = '/admin/recipes/upload-image';

                fetch(uploadUrl, {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result && result.imageUrl) {
                            const range = this.quill.getSelection();
                            this.quill.insertEmbed(range.index, 'image', result.imageUrl);
                        }
                    })
                    .catch(error => {
                        console.error('Error uploading image:', error);
                    });
            }
        };
    }

    const quillInstances = Array.from(editors).map(editor => {
        const quill = new Quill(editor, {
            theme: 'snow',
            modules: {
                toolbar: {
                    container: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline', 'strike'],
                        ['blockquote', 'code-block'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        [{ 'script': 'sub'}, { 'script': 'super' }],
                        [{ 'indent': '-1'}, { 'indent': '+1' }],
                        [{ 'direction': 'rtl' }],
                        [{ 'size': ['small', false, 'large', 'huge'] }],
                        [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                        [{ 'color': [] }, { 'background': [] }],
                        [{ 'font': [] }],
                        [{ 'align': [] }],
                        ['link', 'image'],
                        ['clean']
                    ],
                    handlers: {
                        'image': imageHandler
                    }
                }
            }
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

document.addEventListener('DOMContentLoaded', function () {
    var dropdownSubmenus = document.querySelectorAll('.dropdown-submenu');

    dropdownSubmenus.forEach(function (submenu) {
        submenu.addEventListener('mouseover', function () {
            var submenuDropdown = submenu.querySelector('.dropdown-menu');
            if (submenuDropdown) {
                submenuDropdown.classList.add('show');
            }
        });

        submenu.addEventListener('mouseout', function () {
            var submenuDropdown = submenu.querySelector('.dropdown-menu');
            if (submenuDropdown) {
                submenuDropdown.classList.remove('show');
            }
        });
    });
});


