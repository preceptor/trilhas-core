/**
 * $(selector).navigation( json , options );
 */
(function ($) {

$.fn.navigation = function(contentJson, options)
{
	var content,current,url,
		context = this,
        transitions = ['slideLR','slideRR'],
        transition = 'none',

		next = function(){
			if (content[(current+1)]) {
				current++;
				update();
			}
			return false;
		},

		previous = function(){
			if (content[current-1]) {
				current--;
				update();
			}
			return false;
		},

		createTreeview = function(){
			var i,j,html = '<ul class="first">',
				length = content.length,
				hierachLength = 0;

			for (i = 0; i < length ; i++) {
				if (content[i+1]) {
					if (content[i+1].level > content[i].level) {
						html += '<li><img src="img/plus.jpg" />&nbsp;';
						html += '<a href="#" id="content_' + i + '">';
						html += content[i].title;
						html += '</a><ul>';
					} else {
						html += '<li><a href="#" id="content_' + i + '">';
						html += content[i].title;
						html += '</a></li>';
					}

					if (content[i+1].level < content[i].level) {
						hierachLength = content[i].level - content[i+1].level;
						for (j = 0; j < hierachLength ; j++) {
							html += '</ul></li>';
						}
					}
				} else {
					html += '<li><a href="#" id="content_' + i + '">' + content[i].title + '</a></li>';

                    if (content[i-1]) {
                        if (content[i-1].level > content[i].level) {
                            hierachLength = content[i-1].level - content[i].level;
                            for (j = 0; j < hierachLength ; j++) {
                                html += '</ul></li>';
                            }
                        }
                    }
				}
			}

			html += '</ul>';

			$('.navigation .buttons .treeview div', context).html(html).hide();

            $('.navigation .buttons .treeview .button', context).click(function(){
                $(this).next().slideToggle('fast');
            });
            
			$('.navigation .buttons .treeview div img', context).click(function(){
				var $img = $(this);

                $img.parent().children().eq(2).slideToggle('fast');
				
				if($img.attr('src').indexOf('minus') > -1) {
                    $img.attr('src', $img.attr('src').replace('minus', 'plus'));
                }else{
                    $img.attr('src', $img.attr('src').replace('plus', 'minus'));
                }
				return false;
			});
            
            $('.navigation .buttons .treeview .first ul').hide();

			$('.navigation .buttons .treeview a', context).click(function() {
				current = parseInt(this.id.replace('content_',''));
				$('.navigation .buttons .treeview div', context).hide('fast');
				update();
				return false;
			});
		},

		update = function(){
			var $content = $('.text', context),
                selector = '#' + $content[0].id,
                applyTransition = transition,
                $nextButton = $('.navigation .buttons a.next', context),
                $previousButton = $('.navigation .buttons a.previous', context);
                
                
            $previousButton.show();
            $nextButton.show();
                
			if (!content[current-1]) {
                $previousButton.hide();
            }
            
            if (!content[current+1]) {
                $nextButton.hide();
            }
            
            if (transition == 'random') {
                applyTransition = transitions[Math.floor(Math.random()*2)];
            }
            
            switch (applyTransition) {
                case 'none':
                    $(selector).load(url + content[current].id);
                    break;
                case 'slideRR':
                    Transitions.slideRR(selector, url + content[current].id);
                    break;
                case 'slideLR':
                    Transitions.slideLR(selector, url + content[current].id);
                    break;
//                case 'slideUD':
//                    Transitions.slideUD(selector, url + content[current].id);
//                    break;
//                case 'fade':
//                    Transitions.fade(selector, url + content[current].id);
//                    break;
            }

			$.data(window, 'current_id'   , content[current].id);
			$.data(window, 'current_index', current);
			
			updateBreadCrumb();
		},

		updateBreadCrumb = function(){
			var $bread = $('.navigation .bread span', context),
				itens = getParents(),
				item = null,
				i = 0,
				tmp = [];

			for (i = 0;i < itens.length;i++) {
				item = '<a href="#"  id="bread_content_' + itens[i].index + '">';
				item += itens[i].title + '</a>';
				tmp.push(item);
			}

			tmp.push(content[current].title);
            
			$bread.html(tmp.join(' - '));
			$bread.find('a').click(function(){
				current = parseInt(this.id.replace('bread_content_', ''));
				update();
				return false;
			});
            $(window).scrollTop($(context).offset().top - 37);
		},

		getParents = function(){
			var i,
				response = [],
				level = content[current].level;
			
			for (i = current-1; i > 0; i--) {
				if (content[i]) {
					if (content[i].level < level) {
						content[i].index = i;
						response.push(content[i]);
						level--;
					}
				}
			}

			return response.reverse();
		};

	return this.each(function(){
		var $breadImg = $('.navigation .bread img',this),
			$nextButton = $('.navigation .buttons a.next',this),
			$previousButton = $('.navigation .buttons a.previous',this);
			
		content    = $.parseJSON(contentJson) || ['No content'];
		current    = options.current || 0;
		url        = options.url || 'content/index/view/id/';
		transition = options.transition || 'random';
		
		$nextButton.click(function(){
			next.apply(context);
			return false;
		});
		
		$previousButton.click(function(){
			previous.apply(context);
			return false;
		});

		$(this).append('<div class="cache" style="display: none">');

		update();
		createTreeview();

		$breadImg.click(function(){
            var $tree = $('.navigation .bread .treeview',context);
            if ($tree.css('display') == 'none') {
                $tree.show();
            } else {
                $tree.hide();
            }
			return false;
		}).css('cursor','pointer');
	});
}

var Transitions = {
    slideLR: function(selector, url) {
        $.get(url, function(data){
            $(selector).css('position', 'relative').animate({"left": "-=700px"}, 'slow', function(){
                $(this).html(data)
                       .animate({"left": "+=700px"}, 'slow', function(){
                           $(this).css('position', 'static');
                       });
            });
        });
    },
    slideRR: function(selector, url) {
        $.get(url, function(data){
            $(selector).css('position', 'relative').animate({"left": "+=700px"}, 'slow', function(){
                $(this).html(data)
                       .css('left', '-700px')
                       .animate({"left": "+=700px"}, 'slow', function(){
                           $(this).css('position', 'static');
                       });
            });
        });
    },
    slideUD: function(selector, url) {
        $.get(url, function(data){
            $(selector).slideUp('slow', function(){
                $(this).html(data)
                       .slideDown('slow');
            });
        });
    },
    fade: function(selector, url) {
        $.get(url, function(data){
            $(selector).fadeOut('slow', function(){
                $(this).html(data)
                       .fadeIn('slow');
            });
        });
    }
};

})(jQuery);