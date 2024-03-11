import React from 'react';
import {render} from "react-dom";

// import useRecipes from '../hooks/useRecipes';

function Recipe() {
    return <div>Hello tlm</div>

}

class RecipesElement extends HTMLElement {
    connectedCallback() {
        render(<Recipe/>, this);
    }
}

customElements.define('recipe-list', RecipesElement);
