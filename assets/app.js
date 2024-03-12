import './bootstrap.js';
import './bootstrap';
import {registerReactControllerComponents} from '@symfony/ux-react';

// import $ from 'jquery';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.scss';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

// assets/app.js
registerReactControllerComponents(require.context('./react/controllers', true, /\.(j|t)sx?$/));
import './react/controllers/Recipes.jsx'