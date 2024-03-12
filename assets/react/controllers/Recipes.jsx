import React, {useEffect} from 'react';
import {unmountComponentAtNode} from "react-dom";
import {createRoot} from 'react-dom/client';
import {useRecipesFetch} from "../hooks/useRecipesFetch";
import {Icon} from "../components/Icon";

const dateFormat = {
    dateStyle: 'medium',
    timeStyle: 'short',
}

function Recipes() {
    const {items: recipes, load, loading, count, hasMore} = useRecipesFetch('/api/recipes')

    useEffect(() => {
        load()
    }, [])

    return <div>
        {loading && 'Loading ...'}
        {recipes.map(recipe => <Recipe key={recipe.id} recipe={recipe}/>)}
        <RecipesCount count={count}/>
        {hasMore && <button disabled={loading} className="btn btn-primary" onClick={load}>Load more recipes</button>}
    </div>
}

const Recipe = React.memo(({recipe}) => {
    const createdAtDate = new Date(recipe.createdAt)
    const updatedAtDate = new Date(recipe.updatedAt)
    return (
        <div className="row">
            <h4>
                <p className="text-purple-950">{recipe.name}</p>
                <span
                    className="block text-blue-600">Date of publication: <strong>{createdAtDate.toLocaleString(undefined, dateFormat)}</strong></span>
                <span
                    className="block text-sky-600">Last update: <strong>{updatedAtDate.toLocaleString(undefined, dateFormat)}</strong></span>
            </h4>
            <div className="col-sm-9">
                <p>{recipe.description}</p>
            </div>
        </div>
    );
})


function RecipesCount({count}) {
    return <h3>
        <Icon icon="book"></Icon>
        Number of recipes: {count}
    </h3>
}

class RecipesElement extends HTMLElement {
    connectedCallback() {
        const root = createRoot(this);
        root.render(<Recipes/>);
    }

    disconnectedCallback() {
        unmountComponentAtNode(this)
    }
}

customElements.define('recipes-list', RecipesElement);
