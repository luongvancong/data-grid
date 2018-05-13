var DataTable = {
    defaultOptions: {
        onChangeItem: function(row, e) {},
        onCheckAll: function(e) {}
    },

    getCheckedItems: function() {
        var items = [];
        return $('.data-table-checkbox-item.fa-check-square').toArray();
    },

    onClickCheckAll: function(e) {
        var $element = $(e.target);
        if($element.hasClass('fa-square-o')) {
            $element.removeClass('fa-square-o').addClass('fa-check-square');
            $('.data-table-checkbox-item').removeClass('fa-square-o').addClass('fa-check-square');
        } else {
            $element.addClass('fa-square-o').removeClass('fa-check-square');
            $('.data-table-checkbox-item').addClass('fa-square-o').removeClass('fa-check-square');
        }

        this.defaultOptions.onCheckAll(e);
    },

    onClickCheckItem: function(e) {
        var $element = $(e.target);
        $element.toggleClass('fa-square-o').toggleClass('fa-check-square');

        console.log(this.getCheckedItems());

        this.defaultOptions.onChangeItem($element.parents('tr')[0], e);
    },

    init: function(options) {
        Object.assign(this.defaultOptions, options);

        var checkBoxAllSelector = document.querySelectorAll('.data-table-checkbox-all');
        for(var i = 0; i < checkBoxAllSelector.length; i ++) {
            checkBoxAllSelector[i].addEventListener('click', this.onClickCheckAll.bind(this));
        }

        var checkBoxItemsSelector = document.querySelectorAll('.data-table-checkbox-item');
        for(var i = 0; i < checkBoxItemsSelector.length; i ++) {
            checkBoxItemsSelector[i].addEventListener('click', this.onClickCheckItem.bind(this));
        }
    }
};

