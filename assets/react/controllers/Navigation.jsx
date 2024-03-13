import React, {useEffect} from 'react';
import {createRoot} from "react-dom/client";
import {unmountComponentAtNode} from "../../vendor/react-dom/react-dom.index";

const navLinks = [
    {
        title: 'Home',
        path: '/'
    }, {
        title: 'Recipes',
        path: '/recipes-list'
    }, {
        title: 'Fridge',
        path: '/fridge'
    }, {
        title: 'Login',
        path: '/login'
    }, {
        title: 'Admin',
        path: '/admin'
    },

]

function Navigation() {
    return (
        <nav className="site-navigation">
            <span>NavBar</span>
            <ul>
                {navLinks.map((link, index) => (
                    <li key={index}>
                        <a className="nav-link" href={link.path}>{link.title}</a>
                    </li>
                ))}
            </ul>
        </nav>
    );
}

class NavigationElement extends HTMLElement {
    connectedCallback() {
        const root = createRoot(this);
        root.render(<Navigation/>);
    }

    disconnectedCallback() {
        unmountComponentAtNode(this)
    }
}

customElements.define('navigation-element', NavigationElement);