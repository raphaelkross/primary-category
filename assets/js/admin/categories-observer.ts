/**
 * Categories Observer.
 *
 * Watches changes in the categories widget at Dashboard,
 * for example, when a new category is added. So the user
 * don't need to Publish before having that available in
 * our widget.
 *
 * @author Rafael Angeline.
 * @since  1.0.0
 */

interface Category {
	name: string;
	id: number;
}

class CategoriesObserver {

	/**
	 * Selector to Categories metabox.
	 */
	private categoriesSelector: string = '#categorychecklist';

	/**
	 * Selector to Categories areas (All and Most Used).
	 */
	private categoriesAllSelector: string = '#categorychecklist, #categorychecklist-pop';

	/**
	 * Selector to Primary Category metabox.
	 */
	private primaryCategorySelector: string = '#tenup-primary-category select';

	/**
	 * Store the Categories metabox element.
	 */
	private categoriesElement: JQuery;

	/**
	 * Store the Primary Category metabox element.
	 */
	private primaryCategoryElement: JQuery;

	/**
	 * Default constructor.
	 */
	public constructor() {
		this.categoriesElement = jQuery(this.categoriesSelector);
		this.primaryCategoryElement = jQuery(this.primaryCategorySelector);
	}

	/**
	 * Initialize the logic.
	 */
	public init(): void {
		// Check if plugin should be initialized.
		if ( this.categoriesElement.length > 0 && this.primaryCategoryElement.length > 0 ) {
			// If the elements exist, we run the observer and other methods.
			this.setupEvents();
		}
	}

	/**
	 * Configure the Events Setup.
	 */
	private setupEvents(): void {
		// Configure the mutator observer.
		this.configureMutatorObserver();

		// Configure the onCheck events.
		this.configureOnCheckEvents();
	}

	/**
	 * Configure onCheck events.
	 */
	private configureOnCheckEvents(): void {
		const localAddCategory = this.addCategory.bind(this);
		const localRemoveCategory = this.removeCategory.bind(this);

		// When a category is checked or unchecked, we must add or remove from <select>
		jQuery( this.categoriesAllSelector ).on( 'click', 'label', function( event: JQueryEventObject ) {
			const label = jQuery(this);
			const radio = jQuery(this).find('input');

			// Get information.
			const categoryID: number = Number.parseInt( <string>radio.attr('value') );
			const categoryName: string = label.text().trim();

			const data = <Category>{ id: categoryID, name: categoryName };

			// Check if it's checked...
			if ( radio.is(':checked') ) {
				// Add the category to <dropdown>.
				localAddCategory( data );
			} else {
				// Remove the category from <dropdown>.
				localRemoveCategory( data );
			}
		} );
	}

	/**
	 * Configure MutatorObserver.
	 */
	private configureMutatorObserver(): void {
		// Get the target node.
		const target : Node = <Node>this.categoriesElement.get(0);

		// Create an observer instance.
		const observer = new MutationObserver( ( mutations ) => {
			mutations.forEach( ( mutation ) => {
				const category: Category | boolean = this.extractElementInformation( mutation );

				// If it's a category, we proceed.
				if ( category !== false ) {
					this.addCategory( category as Category );
				}
			});
		});

		// Configuration of the observer.
		var config = { attributes: true, childList: true, characterData: true }

		// Pass in the target node, as well as the observer options.
		observer.observe( target, config );
	}

	/**
	 * Extract element from Mutation.
	 *
	 * @param MutationRecord mutation The mutation record.
	 */
	private extractElementInformation( mutation: MutationRecord ): Category | boolean {
		// Check if it's a valid record.
		if ( mutation.addedNodes instanceof NodeList !== true ||
			mutation.addedNodes.length <= 0 ) {
			return false;
		}

		// Store the first NodeList element.
		const element: JQuery = <JQuery>jQuery(mutation.addedNodes[0]);

		// Get information about the new node.
		const categoryID: number = Number.parseInt( <string>element.find('input').attr('value') );
		const categoryName: string = element.find('label').text().trim();

		// Return the information.
		return <Category>{ id: categoryID, name: categoryName };
	}

	/**
	 * Add new category to dropdown.
	 *
	 * @param Category category The new category to be added.
	 */
	private addCategory( category: Category ) : void {
		// Check if category already exists.
		if ( this.primaryCategoryElement.find('option[value="' + category.id + '"]').length > 0 ) {
			// Already exists.
			return;
		}

		// Append new category to our selector.
		this.primaryCategoryElement.append('<option value="' + category.id + '">' + category.name + '</option>');
	}

	/**
	 * Remove a category from the dropdown.
	 *
	 * @param Category category The new category to be deleted.
	 */
	private removeCategory( category: Category ) : void {
		// Check if category already exists.
		const target = this.primaryCategoryElement.find('option[value="' + category.id + '"]');

		// Exists?
		if ( target.length > 0 ) {
			// Already exists, delete it!
			target.remove();
		}
	}
}

export default CategoriesObserver;
