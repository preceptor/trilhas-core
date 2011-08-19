var Report = {
	init:function(){
		$('#table li a').click(function(){
			var values = $(this).parent().children('input'),
                data = {schema:values[0].value,table:values[1].value};
            
			$('#more').empty();
			$('#table-field').load('report/index/field', data);
            
            return false;
		});
	},

	eventField:function(){
		$('#field .table-field li a').unbind('click').click(function(){
			$('#list').load(this.href, {info: $(this).next().val()});
			return false;
		});
	},

	eventTable:function(){
		$('.button-table').unbind('click').click(function(){
			var $div = $('<div>');
			$('#more').append($div);

			$div.load(this.href, {info: $(this).next().val()});

			$(this).parent().parent().parent().hide();

			return false;
		});
	},
	
	eventMore:function(){
		$('.button-more').unbind('click').click(function(){
			$(this).parent().children('div.list-table').toggle();
			return false;
		});
	},

    eventOrder:function(){
        $('.tablesorter .header').unbind('click').click(function(e){
            var add = e['shiftKey'],
                $children = $(this).children('input'),
                direction = 'ASC';

            if( this.className.indexOf('headerSortUp') > 1 ) {
                direction = 'DESC';
            }
            
            $('#list').load(
                'report/index/order',
                {colunm: $children.eq(0).val(),
                 direction: direction,
                 add: add}
            );
        });
    },

    eventFilter:function(){
        $('#formFilter').unbind('submit').submit(function(){
            $('#list').load(this.action, $(this).serialize());
            return false;
        });
    },

    eventAggregate:function(){
        $('#formAggregate').unbind('submit').submit(function(){
            $('#list').load(this.action, $(this).serialize());
            return false;
        });
    },

    eventColumn:function(){
        $('#formColumn').unbind('submit').submit(function(){
            $('#list').load(this.action, $(this).serialize());
            return false;
        });
    }
}