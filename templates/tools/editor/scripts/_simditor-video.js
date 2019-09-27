(function (root, factory) {
	if (typeof define === 'function' && define.amd) {
		define('simditor-video', ["jquery","simditor"], function (a0,b1) {
			return (root['VideoButton'] = factory(a0,b1));
		});
	} else if (typeof exports === 'object') {
		module.exports = factory(require("jquery"),require("Simditor"));
	} else {
		root['SimditorVideo'] = factory(root["jQuery"],root["Simditor"]);
	}
}(this, function ($, Simditor) {

	var VideoButton, VideoPopover,
	__hasProp = {}.hasOwnProperty,
	__extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
	__slice = [].slice;

	VideoButton = (function(superClass) {
		__extends(VideoButton, superClass);

		function VideoButton() {
			return VideoButton.__super__.constructor.apply(this, arguments);
		}

		VideoButton.prototype.name = 'video';
		VideoButton.prototype.icon = 'video-o';
		VideoButton.prototype.htmlTag = 'iframe';
		VideoButton.prototype.disableTag = 'pre, table';

		VideoButton.prototype.render = function() {
			var args;
			args = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
			VideoButton.__super__.render.apply(this, args);
			return this.popover = new VideoPopover({
				button: this
			});
		};

		VideoButton.prototype._status = function() {
			VideoButton.__super__._status.call(this);

			console.log('iframe');

			if(this.active && !this.editor.selection.rangeAtEndOf(this.node))
			{
				//console.log('show');
				return this.popover.show(this.node);
			}
			else
			{
				//console.log('hide');
				return this.popover.hide();
			}
		};

		VideoButton.prototype.command = function() {
			var $contents, $link, $newBlock, linkText, range, txtNode;
			range = this.editor.selection.range();
			if(this.active)
			{
				console.log(active);
				//txtNode = document.createTextNode(this.node.text());
				//this.node.replaceWith(txtNode);
				//range.selectNode(txtNode);
			}
			else
			{
				$contents = $(range.extractContents());
				$link = $('<iframe>\n</iframe>', {
					src: '',
					width: 600,
					height: 480,
					frameborder: 0,
					allow: 'autoplay; encrypted-media',
					allowfullscreen: ''
				});
				
				if(this.editor.selection.blockNodes().length > 0)
				{
					range.insertNode($link[0]);
				}
				else
				{
					$newBlock = $('<p/>').append($link);
					range.insertNode($newBlock[0]);
				}

				range.selectNodeContents($link[0]);

				this.popover.one('popovershow', (function(_this) {
					return function() {
						_this.popover.urlEl.focus();
						return _this.popover.urlEl[0].select();
						/*if (linkText) {
							_this.popover.urlEl.focus();
							return _this.popover.urlEl[0].select();
						} else {
							//_this.popover.textEl.focus();
							//return _this.popover.textEl[0].select();
						}*/
					};
				})(this));
			}
			this.editor.selection.range(range);
			return this.editor.trigger('valuechanged');
		};

		return VideoButton;

	})(Simditor.Button);

	VideoPopover = (function(superClass) {
		__extends(VideoPopover, superClass);

		function VideoPopover() {
			return VideoPopover.__super__.constructor.apply(this, arguments);
		}

		VideoPopover.prototype.render = function() {
			var tpl;
			tpl = "<div class=\"video-settings\">\n  <div class=\"settings-field\">\n  <div class=\"settings-field\">\n    <label>" + (this._t('linkUrl')) + "</label>\n    <input class=\"link-url\" type=\"text\"/>\n  </div>\n</div>";
			
			this.el.addClass('video-popover').append(tpl);
			
			this.urlEl = this.el.find('.link-url');
			this.urlEl.on('keyup', (function(_this) {
				return function(e) {
					var val;
					if (e.which === 13) {
						return;
					}
					val = _this.urlEl.val();
					if (!(/https?:\/\/|^\//ig.test(val) || !val)) {
						val = 'http://' + val;
					}
					_this.target.attr('src', val);
					return _this.editor.inputManager.throttledValueChanged();
				};
			})(this));
			$([this.urlEl[0]]).on('keydown', (function(_this) {
				return function(e) {
					var range;
					if (e.which === 13 || e.which === 27 || (!e.shiftKey && e.which === 9 && $(e.target).hasClass('link-url'))) {
						e.preventDefault();
						range = document.createRange();
						_this.editor.selection.setRangeAfter(_this.target, range);
						_this.hide();
						return _this.editor.inputManager.throttledValueChanged();
					}
				};
			})(this));
			/*this.unlinkEl.on('click', (function(_this) {
				return function(e) {
					var range, txtNode;
					txtNode = document.createTextNode(_this.target.text());
					_this.target.replaceWith(txtNode);
					_this.hide();
					range = document.createRange();
					_this.editor.selection.setRangeAfter(txtNode, range);
					return _this.editor.inputManager.throttledValueChanged();
				};
			})(this));*/
		};

		VideoPopover.prototype.show = function() {
			var args;
			args = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
			VideoPopover.__super__.show.apply(this, args);
			//this.textEl.val(this.target.text());
			//return this.urlEl.val(this.target.attr('href'));
		};

		return VideoPopover;

	})(Simditor.Popover);

	Simditor.Toolbar.addButton(VideoButton);

	return VideoButton;

}));