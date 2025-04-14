
const ingredientsInput = document.getElementById('ingredients');
const addIngredientButton = document.getElementById('add-ingredient-button');
const ingredientList = document.getElementById('ingredient-list');
const suggestRecipeButton = document.getElementById('suggest-recipe-button');
const recipeSuggestions = document.getElementById('recipe-suggestions');
const savedRecipeList = document.getElementById('saved-recipe-list');
const placeholderText = document.getElementById('placeholder-text');


const messageBox = document.getElementById('message-box');
const messageText = document.getElementById('message-text');
//footer
const currentYearSpan = document.getElementById('current-year');
//header
const accountDropdown = document.getElementById('account-dropdown');
const accountButton = document.getElementById('account-button');
const dropdownMenu = document.getElementById('dropdown-menu');

// --- State Variables ---
let ingredients = []; // Array for current ingredient list (page-specific)
let savedRecipes = JSON.parse(localStorage.getItem('savedRecipes')) || []; //still use local storage for client side saving demo


function showMessage(message, type = 'success', duration = 3000) {
    if (!messageBox || !messageText) return;
    messageText.textContent = message;
    messageBox.className = type === 'success' ? 'success' : 'error';
    messageBox.classList.add('show');
    setTimeout(() => {
        messageBox.classList.remove('show');
    }, duration);
}

function updateDropdownMenu() {
    if (!dropdownMenu) return;
    dropdownMenu.innerHTML = '';

    const loginUrl = '/login';
    const registerUrl = '/register';
    const profileUrl = '/profile';
    const logoutUrl = '/logout';

    const createDropdownItem = (text, href = null, onClick = null, isPost = false, className = '') => {
        // ... createDropdownItem function remains the same ...
         let item; let baseClasses = className; if (href && !isPost) { item = document.createElement('a'); item.href = href; item.textContent = text; item.style.display = 'block'; } else if (isPost && href) { item = document.createElement('button'); item.textContent = text; item.style.display = 'block'; item.onclick = (event) => { event.preventDefault(); const form = document.createElement('form'); form.method = 'POST'; form.action = href; const csrfTokenInput = document.createElement('input'); csrfTokenInput.type = 'hidden'; csrfTokenInput.name = '_token'; csrfTokenInput.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''; form.appendChild(csrfTokenInput); document.body.appendChild(form); form.submit(); }; } else if (onClick) { item = document.createElement('button'); item.textContent = text; item.style.display = 'block'; item.onclick = onClick; } else { item = document.createElement('span'); item.textContent = text; baseClasses += ' px-4 py-3 block text-gray-500'; item.style.display = 'block'; } if (item && (href || onClick)) { item.className = `w-full text-left px-4 py-3 text-gray-700 hover:bg-gray-100 transition duration-150 ease-in-out ${baseClasses}`; } else if (item) { item.className = baseClasses; } if (isPost && href === logoutUrl && item.tagName === 'BUTTON') { item.classList.add('logout-button'); } return item;
    };

    // --- Read auth status using getAttribute AND TRIM ---
    const authAttributeValue = document.body.getAttribute('data-is-authenticated');
    const trimmedValue = authAttributeValue ? authAttributeValue.trim() : null; // Trim whitespace
    const isAuthenticated = trimmedValue === 'true'; // Compare trimmed value
    // ----------------------------------------------------

    // --- Log the raw attribute, its type, and the result ---
    console.log('Raw data-is-authenticated value:', authAttributeValue);
    console.log('Type of attribute value:', typeof authAttributeValue); // Log the type
    console.log('Trimmed attribute value:', trimmedValue);
    console.log('Auth status evaluated by script.js:', isAuthenticated);
    // --------------------------------------------------

    if (isAuthenticated) {
        // --- Logged In View ---
        dropdownMenu.appendChild(createDropdownItem('Profile', profileUrl));
        dropdownMenu.appendChild(createDropdownItem('Logout', logoutUrl, null, true));

    } else {
        // --- Logged Out View ---
        dropdownMenu.appendChild(createDropdownItem('Login', loginUrl));
        dropdownMenu.appendChild(createDropdownItem('Sign Up', registerUrl));
    }
}

// --- Event Listeners for Dropdown ---
if (accountButton) {
    accountButton.addEventListener('click', (event) => {
        event.stopPropagation();
        accountDropdown.classList.toggle('show');
    });
}
window.addEventListener('click', (event) => {
    if (accountDropdown && !accountDropdown.contains(event.target)) {
        accountDropdown.classList.remove('show');
    }
});

function renderIngredientList() {
    if (!ingredientList) return;
    ingredientList.innerHTML = '';

    const emojiMap = [
        { terms: ['beef', 'pork', 'steak', 'bacon', 'ham', 'sausage'], emoji: '🥩' }, 
        { terms: ['chicken'],emoji:'🍗'},
        { terms: ['fish', 'salmon', 'tuna', 'shrimp', 'crab', 'lobster', 'oyster'], emoji: '🐟' },
        { terms: ['egg', 'eggs'], emoji: '🥚' },
        { terms: ['milk', 'cheese', 'yogurt','cream'], emoji: '🧀' }, 
        { terms: ['butter','ghee'],emoji:'🧈'},
        { terms: ['bread', 'baguette', 'croissant', 'toast'], emoji: '🍞' },
        { terms: ['noodle', 'pasta', 'spaghetti', 'ramen'], emoji: '🍝' },
        { terms: ['rice'], emoji: '🍚' },
        { terms: ['apple', 'pear'], emoji: '🍎' },
        { terms: ['banana'], emoji: '🍌' },
        { terms: ['orange', 'tangerine', 'clementine'], emoji: '🍊' },
        { terms: ['lemon', 'lime'], emoji: '🍋' },
        { terms: ['berry', 'strawberry', 'blueberry', 'raspberry'], emoji: '🍓' },
        { terms: ['grape'], emoji: '🍇' },
        { terms: ['melon', 'watermelon'], emoji: '🍈' },
        { terms: ['pineapple'], emoji: '🍍' },
        { terms: ['mango'], emoji: '🥭' },
        { terms: ['avocado'], emoji: '🥑' },
        { terms: ['eggplant'], emoji: '🍆' },
        { terms: ['carrot'], emoji: '🥕' },
        { terms: ['corn'], emoji: '🌽' },
        { terms: ['hot pepper', 'chili', 'jalapeño'], emoji: '🌶️' },
        { terms: ['cucumber', 'pickle'], emoji: '🥒' },
        { terms: ['broccoli'], emoji: '🥦' },
        { terms: ['mushroom'], emoji: '🍄' },
        { terms: ['peanut'], emoji: '🥜' },
        { terms: ['chestnut'], emoji: '🌰' },
        { terms: ['garlic', 'onion', 'shallot'], emoji: '🧄' },
        { terms: ['ginger'], emoji: '🍂' },
        { terms: ['potato', 'sweet potato'], emoji: '🥔' },
        { terms: ['cookie', 'biscuit'], emoji: '🍪' },
        { terms: ['cake', 'cupcake'], emoji: '🍰' },
        { terms: ['chocolate', 'candy'], emoji: '🍫' },
        { terms: ['honey'], emoji: '🍯' },
        { terms: ['ice cream'], emoji: '🍨' },
        { terms: ['doughnut'], emoji: '🍩' },
        { terms: ['popcorn'], emoji: '🍿' },
        { terms: ['coffee','tea'], emoji: '☕' },
        { terms: ['cocktail'], emoji: '🍹' },
        { terms: ['salt'], emoji: '🧂' },
        { terms: ['herb', 'basil', 'parsley', 'cilantro', 'dill'], emoji: '🌿' },
        { terms: ['spice', 'pepper', 'cumin', 'paprika', 'turmeric'], emoji: '🧂' },
        { terms: ['oil', 'olive oil'], emoji: '🧈' },
        { terms: ['vinegar'], emoji: '🍶' },
        { terms: ['soy sauce'], emoji: '🔸' },
        { terms: ['tofu', 'tempeh'], emoji: '🥟' },
        { terms: ['sushi'], emoji: '🍣' },
        { terms: ['taco'], emoji: '🌮' },
        { terms: ['burrito'], emoji: '🌯' },
        { terms: ['pizza'], emoji: '🍕' },
        { terms: ['hamburger'], emoji: '🍔' },
        { terms: ['fries', 'french fries'], emoji: '🍟' },
        { terms: ['sandwich'], emoji: '🥪' },
        { terms: ['falafel'], emoji: '🧆' }, 
        { terms: ['hummus'], emoji: '🥣' },
        { terms: ['soup'], emoji: '🍲' },
        { terms: ['salad'], emoji: '🥗' },
        { terms: ['water'], emoji: '💧' },
        { terms: ['tomato'], emoji: '🍅' },
        { terms: ['bread'], emoji: '🍞' }
    ];

    ingredients.forEach(ingredient => {
        const listItem = document.createElement('li');
        listItem.className = "ingredient-item";
        const lowerIngredient = ingredient.toLowerCase().trim();
        let icon = '🛒'; 
        for (const category of emojiMap) {
            if (category.terms.some(term => lowerIngredient.includes(term))) {
                icon = category.emoji;
                break;
            }
        }
        listItem.innerHTML = `
            <span><span class="icon" aria-hidden="true">${icon}</span> ${ingredient}</span>
            <button class="remove-btn" data-ingredient="${ingredient}" aria-label="Remove ${ingredient}">✕</button>
        `;
        ingredientList.appendChild(listItem);
    });

     ingredientList.onclick = function(event) {
         if (event.target.classList.contains('remove-btn')) {
             const ingredientToRemove = event.target.dataset.ingredient;
             ingredients = ingredients.filter(item => item !== ingredientToRemove);
             renderIngredientList(); //re-render the list
         }
     };

    if (placeholderText && recipeSuggestions) {
         placeholderText.style.display = ingredients.length === 0 && recipeSuggestions.children.length <= 1 ? 'block' : 'none';
    }
}

function addIngredient() {
    if (!ingredientsInput) return; 
    const ingredient = ingredientsInput.value.trim();

    if (ingredient !== '') {
        if (!ingredients.includes(ingredient)) {
            ingredients.push(ingredient);
            renderIngredientList();
            ingredientsInput.value = '';
            ingredientsInput.focus();
        } else {
            showMessage('Ingredient already added.', 'error');
        }
    } else {
        showMessage('Please enter an ingredient.', 'error');
    }
}

async function getRecipes() { //async for await
    if (!suggestRecipeButton || !recipeSuggestions || ingredients.length === 0) {
        if(ingredients.length === 0) showMessage('Please add ingredients first!', 'error');
        return;
   }

   suggestRecipeButton.innerHTML = `<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Finding Recipes...`;
   suggestRecipeButton.disabled = true;
   recipeSuggestions.innerHTML = ''; 
   if (placeholderText) placeholderText.style.display = 'none';


   const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
   if (!csrfToken) {
       console.error('CSRF token not found!');
       showMessage('Error: Missing security token. Please refresh.', 'error');
       if (suggestRecipeButton) {
           suggestRecipeButton.innerHTML = '✨ Find Recipes';
           suggestRecipeButton.disabled = false;
       }
       return;
   }

   try {
       const response = await fetch('/api/suggest-recipes', { 
           method: 'POST',
           headers: {
               'Content-Type': 'application/json',
               'X-CSRF-TOKEN': csrfToken,
               'Accept': 'application/json', 
           },
           body: JSON.stringify({ ingredients: ingredients })
       });

       if (!response.ok) {
           let errorMsg = `HTTP error ${response.status}`;
           try {
               const errorData = await response.json(); // Use await
               errorMsg = errorData.error || errorData.message || errorMsg;
           } catch (e) { /* ignore parsing error if response wasnt JSON */ }
           throw new Error(errorMsg); 
       }

       const data = await response.json(); //parse successful JSON response

       handleRecipeResponse(data);

   } catch (error) {
       console.error('Error fetching recipes:', error);
       showMessage(error.message || 'Failed to fetch recipes.', 'error');
       handleRecipeResponse({ error: error.message || 'Failed to fetch' });
   } finally {
       if (suggestRecipeButton) {
           suggestRecipeButton.innerHTML = '✨ Find Recipes';
           suggestRecipeButton.disabled = false;
       }
   }
}

/**
* Helper function to process the recipe data (already provided in previous script.js)
* @param {object[] | object} responseData - Array of recipe objects or an error object.
*/
function handleRecipeResponse(responseData) {
    if (Array.isArray(responseData) && responseData.length > 0) {
       if (recipeSuggestions) recipeSuggestions.innerHTML = '';
       responseData.forEach(recipe => {
           const recipeDiv = createRecipeElement(recipe, true);
           if (recipeSuggestions) recipeSuggestions.appendChild(recipeDiv);
       });
   } else if (responseData && responseData.error) {
        if (recipeSuggestions) recipeSuggestions.innerHTML = `<p class="text-red-600 text-center py-6">Could not fetch recipes: ${responseData.error}</p>`;
   } else {
       if (recipeSuggestions) recipeSuggestions.innerHTML = `<p class="text-gray-500 text-center py-6">Couldn't find recipes matching your ingredients. Try adding more!</p>`;
   }
   if (typeof renderIngredientList === 'function') {
       renderIngredientList();
   }
}

function saveRecipe(recipe) {
    if (!recipe || !recipe.name) {
        showMessage('Cannot save invalid recipe data.', 'error');
        return;
    }
    if (savedRecipes.some(saved => saved.name === recipe.name)) {
        showMessage('Recipe already saved!', 'error');
        return;
    }
    recipe.savedDate = new Date().toISOString();
    savedRecipes.push(recipe);
    localStorage.setItem('savedRecipes', JSON.stringify(savedRecipes));
    renderSavedRecipes();   
    showMessage(`${recipe.name} saved!`, 'success');

     // TODO save the recipe to the backend for the logged-in user 
     /*
     if (window.isAuthenticated) {
         fetch('/api/saved-recipes', {
             method: 'POST',
             headers: {
                 'Content-Type': 'application/json',
                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                 'Accept': 'application/json',
             },
             body: JSON.stringify(recipe) // Send the whole recipe object
         })
         .then(response => response.json())
         .then(data => {
             console.log('Recipe saved to backend:', data);
             // Optionally update UI based on backend response if needed
         })
         .catch(error => {
             console.error('Error saving recipe to backend:', error);
             showMessage('Could not save recipe to your account.', 'error');
             // Optionally remove from local storage if backend save failed?
         });
     }
     */
}


 //removes a recipe from local storage and updates the UI.

function removeRecipe(recipeNameToRemove) {
    savedRecipes = savedRecipes.filter(recipe => recipe.name !== recipeNameToRemove);
    localStorage.setItem('savedRecipes', JSON.stringify(savedRecipes));
    renderSavedRecipes(); // Update list if it exists
    showMessage('Recipe removed.', 'success');

     // TODO remove the recipe from the backend for the logged-in user 
     /*
      if (window.isAuthenticated) {
         fetch(`/api/saved-recipes/${encodeURIComponent(recipeNameToRemove)}`, {
             method: 'DELETE',
             headers: {
                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                 'Accept': 'application/json',
             }
         })
         .then(response => response.json())
         .then(data => {
             console.log('Recipe removed from backend:', data);
         })
         .catch(error => {
             console.error('Error removing recipe from backend:', error);
             showMessage('Could not remove recipe from your account.', 'error');
         });
     }
     */
}

/**
 * Renders the list of saved recipes from local storage.
 */
function renderSavedRecipes() {
    if (!savedRecipeList) return; // Only run if the list element exists
    savedRecipeList.innerHTML = '';

    //TODO  fetch saved recipes via AJAX if user is logged in, instead of just using local storage
    /*
    if (window.isAuthenticated) {
        fetch('/api/saved-recipes')
            .then(response => response.json())
            .then(data => {
                savedRecipes = data; // Update local state with data from backend
                localStorage.setItem('savedRecipes', JSON.stringify(savedRecipes));
                populateSavedRecipeList(savedRecipes); 
            })
            .catch(error => {
                console.error('Error fetching saved recipes:', error);
                savedRecipeList.innerHTML = `<p class="text-red-500 italic text-center py-4">Could not load saved recipes.</p>`;
            });
    } else {
        populateSavedRecipeList(savedRecipes);
    }
    */
   populateSavedRecipeList(savedRecipes);
}

/**
* Helper function to populate the saved recipe list UL element.
* @param {Array} recipesToRender - Array of saved recipe objects.
*/
function populateSavedRecipeList(recipesToRender) {
    if (!savedRecipeList) return;
    savedRecipeList.innerHTML = ''; // Clear first

    const sortedRecipes = recipesToRender.sort((a, b) => new Date(b.savedDate || 0) - new Date(a.savedDate || 0));

    if (sortedRecipes.length === 0) {
        savedRecipeList.innerHTML = `<p class="text-gray-500 italic text-center py-4">You haven't saved any recipes yet.</p>`;
        return;
    }

    sortedRecipes.forEach(recipe => {
        const listItem = document.createElement('li');
        listItem.className = "recipe-card flex justify-between items-center";
        const infoDiv = document.createElement('div');
        const nameSpan = document.createElement('span');
        nameSpan.textContent = recipe.name;
        nameSpan.className = "font-semibold text-lg text-gray-800 block cursor-pointer hover:text-emerald-600";
        nameSpan.onclick = () => displayRecipeDetails(recipe);
        infoDiv.appendChild(nameSpan);
        if(recipe.savedDate) { const dateSpan = document.createElement('span'); dateSpan.textContent = `Saved: ${new Date(recipe.savedDate).toLocaleDateString()}`; dateSpan.className = "text-xs text-gray-500 block mt-1"; infoDiv.appendChild(dateSpan); }
        const buttonGroup = document.createElement('div'); buttonGroup.className = "flex-shrink-0 flex gap-2";
        const detailsButton = document.createElement('button'); detailsButton.textContent = 'Details'; detailsButton.className = "btn btn-indigo text-xs px-3 py-1"; detailsButton.onclick = () => displayRecipeDetails(recipe);
        const removeButton = document.createElement('button'); removeButton.textContent = 'Remove'; removeButton.className = "btn btn-danger text-xs px-3 py-1"; removeButton.onclick = (e) => { e.stopPropagation(); removeRecipe(recipe.name); };
        buttonGroup.appendChild(detailsButton); buttonGroup.appendChild(removeButton); listItem.appendChild(infoDiv); listItem.appendChild(buttonGroup); savedRecipeList.appendChild(listItem);
    });
}

//reates element representing a recipe card.
function createRecipeElement(recipe, includeActions = false) {
    const recipeDiv = document.createElement('div'); recipeDiv.className = "recipe-card"; const nameHeading = document.createElement('h3'); nameHeading.className = "text-xl font-semibold text-gray-800 mb-1"; nameHeading.textContent = recipe.name || "Unnamed Recipe"; const metaInfo = document.createElement('div'); metaInfo.className = "meta-info"; metaInfo.innerHTML = ` ${recipe.estimatedPrepTime ? `<span><svg class="w-4 h-4 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg> ${recipe.estimatedPrepTime}</span>` : ''} ${recipe.difficulty ? `<span><svg class="w-4 h-4 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg> ${recipe.difficulty}</span>` : ''} `; const descriptionPara = document.createElement('p'); descriptionPara.className = "text-gray-600 mb-3"; descriptionPara.textContent = recipe.description || "No description available."; const stepsHeading = document.createElement('h4'); stepsHeading.className = "text-sm font-semibold text-gray-700 mt-4 mb-1 uppercase tracking-wide"; stepsHeading.textContent = "Instructions:"; const stepsList = document.createElement('ul'); stepsList.className = "list-decimal list-inside text-gray-600 space-y-1 pl-1"; if (Array.isArray(recipe.steps) && recipe.steps.length > 0) { recipe.steps.forEach(step => { const stepItem = document.createElement('li'); stepItem.textContent = step; stepsList.appendChild(stepItem); }); } else { stepsList.innerHTML = '<li class="italic text-gray-500">No steps provided.</li>'; } recipeDiv.appendChild(nameHeading); recipeDiv.appendChild(metaInfo); recipeDiv.appendChild(descriptionPara); recipeDiv.appendChild(stepsHeading); recipeDiv.appendChild(stepsList); if (includeActions) { const actionsDiv = document.createElement('div'); actionsDiv.className = "actions"; const saveButton = document.createElement('button'); saveButton.innerHTML = `<svg class="w-4 h-4 mr-1 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.5 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0Z" /></svg> Save`; saveButton.className = "btn btn-secondary text-sm px-3 py-1"; saveButton.onclick = () => saveRecipe(recipe); const shareButton = document.createElement('button'); shareButton.innerHTML = `<svg class="w-4 h-4 mr-1 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z" /></svg> Share`; shareButton.className = "btn btn-ghost text-sm px-3 py-1"; shareButton.onclick = () => showMessage('Share feature coming soon!', 'success', 1500); actionsDiv.appendChild(saveButton); actionsDiv.appendChild(shareButton); recipeDiv.appendChild(actionsDiv); } return recipeDiv;
}

//pop up recipe
function displayRecipeDetails(recipe) {
    //modal Structure
    const modal = document.createElement('div'); modal.className = "modal fixed z-50 inset-0 overflow-y-auto bg-black bg-opacity-60 flex items-center justify-center p-4"; modal.addEventListener('click', (e) => { if (e.target === modal) { modal.remove(); } }); const modalContent = document.createElement('div'); modalContent.className = "modal-content bg-white rounded-lg shadow-xl p-6 w-full max-w-lg max-h-[85vh] overflow-y-auto";
    //populate Modal
    const recipeDetailsDiv = createRecipeElement(recipe, false); modalContent.appendChild(recipeDetailsDiv); const closeButton = document.createElement('button'); closeButton.textContent = 'Close'; closeButton.className = "btn btn-primary mt-6 block mx-auto"; closeButton.onclick = () => modal.remove(); modalContent.appendChild(closeButton);
    //add to Page
    modal.appendChild(modalContent); document.body.appendChild(modal); modal.style.animation = 'fadeIn 0.3s ease-out';
}
//run setup code after the DOM is fully loaded
document.addEventListener('DOMContentLoaded', () => {
    // Set footer year
    if(currentYearSpan) currentYearSpan.textContent = new Date().getFullYear();

    updateDropdownMenu(); 

    if (document.getElementById('recipe-generator-area')) {
        if (addIngredientButton) addIngredientButton.addEventListener('click', addIngredient);
        if (ingredientsInput) ingredientsInput.addEventListener('keydown', (event) => {
            if (event.key === 'Enter') { event.preventDefault(); addIngredient(); }
        });
        if (suggestRecipeButton) suggestRecipeButton.addEventListener('click', getRecipes);
        if (ingredientList) renderIngredientList();
        if (savedRecipeList) renderSavedRecipes();
    }

    const resendBtn = document.getElementById('resend-otp-button'); 
    if (resendBtn) {
//add a listener
    }
    const backToLoginBtn = document.getElementById('back-to-login-button'); 
     if (backToLoginBtn) {
     }

});
