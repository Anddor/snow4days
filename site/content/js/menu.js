/**
 * Created by thorben on 12.11.15.
 */
/**
 * Makes sure only one element is "selected" at a time, and makes use of a
 * delay to improve usability.
 *
 * This object's onFocusChange method needs to be added as an event listener
 * to the elements which you want to mark with the class "show" when selected.
 * The events should be onmouseenter and onmouseleave.
 */
function DropDownMenu() {
    this.currently_active = null;
    this.candidate = null;
    this.timer = null;

    /**
     * Event listener for elements which should get class "show" when hovered over.
     * @param new_focus The new element which has gained focus
     */
    this.onFocusChange = function(new_focus) {
        // Discard previous timer
        if (this.timer != null) {
            clearTimeout(this.timer);
        }

        // Are we losing focus?
        if (new_focus === null) {
            // Yes, hide the menu after some time
            this.start(this.hide);
        } else {
            // Has the currently active menu regained focus?
            if (new_focus === this.currently_active) {
                // Do nothing
            } else {
                // Focus has shifted
                this.candidate = new_focus;
                this.start(this.show);
            }

        }
    };
    this.onFocusChange = this.onFocusChange.bind(this);


    /**
     * Shorthand for setTimeout, used to delay an action some time (in case
     * the mouse pointer isn't going to rest over this element).
     * @param callable Function to be called after some time, if the current
     * focus stays the same.
     */
    this.start = function(callable) {
        this.timer = window.setTimeout(callable, 500);
    };
    this.start = this.start.bind(this);


    /**
     * Hide the currently visible drop down menu.
     */
    this.hide = function() {
        if (this.currently_active != null)
        {
            this.currently_active.classList.remove( "show" );
            this.currently_active = null;
        }
    };
    this.hide = this.hide.bind(this);


    /**
     * Make {@link candidate} the new visible drop-down menu.
     */
    this.show = function() {
        // Hide whatever was shown
        this.hide();
        // Set candidate as the active one
        this.currently_active = this.candidate;
        this.candidate = null;
        // and actually make it visible
        this.currently_active.classList.add("show");
    };
    this.show = this.show.bind(this);
}


var menu = new DropDownMenu();

// Assign event listeners
window.addEventListener("load", function(){
    var ulElements = document.getElementById("navbar").getElementsByClassName("level2");
    for (var i = 0; i < ulElements.length; ++i) {
        var liElements = ulElements.item(i).getElementsByTagName("li");
        for (var j = 0; j < liElements.length; ++j) {
            var liElement = liElements.item(j);
            var eventListenerEnter = function () {
                menu.onFocusChange(this);
            }.bind(liElement);
            var eventListenerLeave = function() {
                menu.onFocusChange(null);
            }.bind(liElement);
            liElement.addEventListener("mouseover", eventListenerEnter);
            liElement.addEventListener("mouseout", eventListenerLeave);

        }
    }
});