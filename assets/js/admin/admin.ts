/**
 * Primary Category Entry Point.
 *
 * Here the components used in the plugin are initialized.
 *
 * @author Rafael Angeline.
 * @since  1.0.0
 */

import CategoriesObserver from './categories-observer';

// Run when document is ready.
jQuery(document).ready(function() {

	// Initialize the Categories Observer.
	const categoriesObserver = new CategoriesObserver();
	// Start things up!
	categoriesObserver.init();

});

