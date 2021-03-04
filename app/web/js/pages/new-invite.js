App.extend({
    Widgets: {}
});

App.Widgets.NewInvite = (function ($, document, ModalComponent) {
    let pub = {
        name: 'App.Widgets.NewInvite',
        depends: ['App.Components.Modal'],
        events: ['click', 'focus', 'blur', 'keydown', 'change', 'submit'],

        $input: $('#newInviteInput'),
        $inputBox: $('.new-invite__box'),
        $inputWrap: $('.new-invite__box-inner'),
        inputWrapFocusClass: 'focus',
        $inputErrorElement: $('#newInviteInputError'),
        inputError: '',

        $select: $('#newInviteSelect'),
        $selectErrorElement: $('#newInviteSelectError'),
        $dropdownBtn: $('#newInviteDropdownBtn'),
        $dropdownMenu: $('#newInviteDropdownMenu'),

        $tagTemplate: $('#newInviteTagTemplate'),
        tagSelector: '.new-invite__tag',
        tagError: '',
        removeTagSelector: '.new-invite__tag-cancel',

        /* Event listeners on $inputWrap */
        inputWrapCallbacks: function () {
            this.$inputWrap.on(this.events.click, function (event) {
                let target = event.target;

                if (target === this) {
                    pub.$input.focus();
                } else if ($(target).is(pub.removeTagSelector)) {
                    removeInviteTag(target);
                }
            });
        },

        /* Event listeners on $input */
        inputCallbacks: function () {
            let $input = this.$input,
                focusClass = this.inputWrapFocusClass,
                events = this.events;

            $input.on(events.focus, function () {
                /* Add focus class on inputWrap when input is focused */
                pub.$inputWrap.addClass(focusClass);
            }).on(events.blur, function () {
                /* Remove focus class on inputWrap when the user leaves the input and validate */
                pub.$inputWrap.removeClass(focusClass);
                pub.validateInput();
            });

            $input.on(events.keydown, function (event) {
                hideEmailError();

                /* Validate input once the comma (,) key is pressed */
                if (event.which === 188) { //comma (,)
                    pub.validateInput();
                    return false;
                }

                /* Remove the last tag if backspace is pressed when the input is empty */
                if (event.which === 8 && $input.val() === '') {
                    removeInviteTag(pub.getInviteTags().last());
                }
            });

        },
        /* Event listeners on $select */
        selectCallbacks: function () {
            this.$select.on(this.events.change, function () {
                hideSelectError();
            });
        },

        /* Event listeners on the form */
        formCallbacks: function () {
            let form = this.$form = this.$inputWrap.closest('form');
            form.on(this.events.submit, function () {
                let isInputValid = pub.validateInput(),
                    isTagValid = pub.validateTags(),
                    isSelectValid = pub.validateSelect();

                if (isInputValid && isTagValid && isSelectValid) {
                    /* Disable the submit button before submitting */
                    form.closest('.modal-content').find(ModalComponent.submitFormButtonSelector).attr('disabled', true);
                    return true;
                }
                return false;
            });

        },

        /* Event listeners on the modal */
        modalCallbacks: function () {
            let modal = this.$inputWrap.closest('.modal');

            modal.on('shown.bs.modal', function () {
                pub.$input.focus();
            });
            modal.on('hidden.bs.modal', function () {
                pub.resetForm();
            });
        },

        /* Set dropdown menu item as the active item */
        setDropdownItemAsActive: function (element) {
            let $element = $(element);
            this.$dropdownMenu.find('li').removeClass('active');
            $element.addClass('active');
            this.$dropdownBtn.find('[data-name]').html($element.data('name'));
            this.$select.val($element.data('value')).trigger(this.events.change);
            this.adjustInputBoxSize();
        },

        /* adjust $inputBox size when the dropdown changes */
        adjustInputBoxSize: function () {
            let $btn = this.$dropdownBtn,
                minWidth = parseInt($btn.css('min-width')),
                width = parseInt($btn.css('width'));

            /* use the larger width */
            this.$inputBox.css({'margin-right': (width > minWidth) ? width : minWidth});
        },

        /* Event listeners on the dropdown menu items */
        dropdownMenuItemCallbacks: function () {
            this.$dropdownMenu.find('li').on(this.events.click, function (event) {
                event.preventDefault();
                pub.setDropdownItemAsActive(this);
            });
        },

        /* Set the initial value of the dropdown with $select's value */
        setInitialDropdownMenuItem: function () {
            let value = this.$select.val();
            let item = this.$dropdownMenu.find('li').filter(function () {
                return $(this).data('value') === value;
            });

            this.setDropdownItemAsActive(item);
        },

        /* Get all the invite tags */
        getInviteTags: function () {
            return this.$inputWrap.find(this.tagSelector);
        },

        /* Validate tags */
        validateTags: function () {
            let length = this.getInviteTags().length;

            if (length) {
                hideEmailError();
            } else {
                showEmailError('tag-msg');
            }

            return !!(length);
        },

        /* Validate $input */
        validateInput: function () {
            let val = this.$input.val().trim();

            if (val === '') {
                return true;
            }
            let isValid = validateEmail(val);

            if (isValid) {
                hideEmailError();
                addInviteTag();
            } else {
                showEmailError('input-msg');
            }

            return isValid;
        },


        /* Validate $select */
        validateSelect: function () {
            let isValid = !!(this.$select.val());
            if (isValid) {
                hideSelectError();
            } else {
                showSelectError();
            }
            return isValid;
        },

        resetForm: function () {
            hideEmailError();
            hideSelectError();
            this.$input.val('');
            this.$inputWrap.find(this.removeTagSelector).each(function () {
                removeInviteTag(this);
            });
            this.$dropdownMenu.find('li').filter(function () {
                return ($(this).data('value') === '');
            }).trigger(this.events.click);
        },

        init: function () {
            this.inputWrapCallbacks();
            this.inputCallbacks();
            this.selectCallbacks();
            this.formCallbacks();
            this.modalCallbacks();
            buildDropdownMenu();
            this.dropdownMenuItemCallbacks();
            this.setInitialDropdownMenuItem();
        }


    };

    /**
     * Simple email validation function
     * @param {string} email
     * @returns {boolean} true if email is valid
     */
    function validateEmail(email)
    {
        let filter = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,20}(?:\.[a-z]{2})?)$/i;
        return !!filter.test(email);
    }

    function addInviteTag()
    {
        let $input = pub.$input,
            email = $input.val().trim(),
            text = document.createTextNode(email),
            $tag = $(pub.$tagTemplate.html()),
            $tagInput = $tag.find('input');

        $tagInput.val(email);
        $(text).insertBefore($tagInput);
        $tag.insertBefore($input);
        $input.val('');
    }

    /**
     * Remove an invite tag
     * @param {HTMLElement} element the tag or it's remove element button
     */
    function removeInviteTag(element)
    {
        $(element).closest(pub.tagSelector).remove();
    }

    /**
     * Show the email validation error
     */
    function showEmailError(type)
    {
        type = type || 'input-msg';
        let $wrap = pub.$inputWrap,
            $error = pub.$inputErrorElement;
        $wrap.addClass('error');
        $error.html($error.data(type)).removeClass('hide');
    }

    /**
     * Hide the email validation error
     */
    function hideEmailError()
    {
        let $wrap = pub.$inputWrap,
            $error = pub.$inputErrorElement;
        $wrap.removeClass('error');
        $error.addClass('hide');
    }

    function hideSelectError()
    {
        pub.$selectErrorElement.addClass('hide');
    }

    function showSelectError()
    {
        pub.$selectErrorElement.removeClass('hide');
    }

    /**
     * Build the dropdown menu
     */
    function buildDropdownMenu()
    {
        let $select = pub.$select,
            $dropdownMenu = pub.$dropdownMenu;

        $select.find('option').each(function () {
            let $option = $(this),
                $list = $('<li><a></a></li>'),
                label, name, value = $option.attr('value');

            if (value === '') {
                label = 'None';
            } else {
                label = $option.html();
            }
            name = $option.html();

            $list.data({'value': value, 'name': name, 'label': label})
                .find('a').html(label);

            $dropdownMenu.append($list);
        });

    }

    return pub;
})(jQuery, document, App.Components.Modal);

App.initModule(App.Widgets.NewInvite);


