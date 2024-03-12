import React, {useEffect} from 'react';
import {unmountComponentAtNode} from "react-dom";
import {createRoot} from 'react-dom/client';
import {useRecipeFetch} from "../hooks/useRecipeFetch";
import {Icon} from "../components/Icon";

function Recipe() {
    const {items: recipes, load, loading, count, hasMore} = useRecipeFetch('/api/recipes')

    useEffect(() => {
        load()
    }, [])

    return <div>
        {loading && 'Loading ...'}
        {JSON.stringify(recipes)}
        <RecipesCount count={count}/>
        {hasMore && <button disabled={loading} className="btn btn-primary" onClick={load}>Load more recipes</button> }
    </div>

}

function RecipesCount({count}) {
    return <h3>
        <Icon icon="book"></Icon>
        Number of recipes: {count}
    </h3>
}

class RecipesElement extends HTMLElement {
    connectedCallback() {
        const root = createRoot(this);
        root.render(<Recipe/>);
    }

    disconnectedCallback() {
        unmountComponentAtNode(this)
    }
}

customElements.define('recipe-list', RecipesElement);
