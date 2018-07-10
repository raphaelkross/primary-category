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
declare class CategoriesObserver {
    /**
     * Selector to Categories metabox.
     */
    private categoriesSelector;
    /**
     * Selector to Primary Category metabox.
     */
    private primaryCategorySelector;
    /**
     * Store the Categories metabox element.
     */
    private categoriesElement;
    /**
     * Store the Primary Category metabox element.
     */
    private primaryCategoryElement;
    /**
     * Default constructor.
     */
    constructor();
    /**
     * Initialize the logic.
     */
    init(): void;
    /**
     * Configure the Events Setup.
     */
    private setupEvents;
    /**
     * Configure onCheck events.
     */
    private configureOnCheckEvents;
    /**
     * Configure MutatorObserver.
     */
    private configureMutatorObserver;
    /**
     * Extract element from Mutation.
     *
     * @param MutationRecord mutation The mutation record.
     */
    private extractElementInformation;
    /**
     * Add new category to dropdown.
     *
     * @param Category category The new category to be added.
     */
    private addCategory;
    /**
     * Remove a category from the dropdown.
     *
     * @param Category category The new category to be deleted.
     */
    private removeCategory;
}
export default CategoriesObserver;
