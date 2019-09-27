(function() {
	var __bind = function(fn, me){ return function(){ return fn.apply(me, arguments); }; },
		__hasProp = {}.hasOwnProperty,
		__extends = function(child, parent) { for (var key in parent) { if (__hasProp.call(parent, key)) child[key] = parent[key]; } function ctor() { this.constructor = child; } ctor.prototype = parent.prototype; child.prototype = new ctor(); child.__super__ = parent.prototype; return child; },
		__slice = [].slice;

	(function(factory) {
		if ((typeof define === 'function') && define.amd) {
			return define(['simditor', 'video'], factory);
		} else {
			return factory(window.Simditor, window.video);
		}
	})(function(Simditor, _video) {
		var VideoButton, VideoPopover;
		VideoButton = (function(_super) {
			__extends(VideoButton, _super);

			function VideoButton() {
				this.command = __bind(this.command, this);
				return VideoButton.__super__.constructor.apply(this, arguments);
			}

			VideoButton.prototype.name = 'video';
			VideoButton.prototype.icon = 'video-o';
			VideoButton.prototype.htmlTag = 'embed, iframe';
			VideoButton.prototype.disableTag = 'pre, table, div';
			VideoButton.prototype.videoPlaceholder = 'video';
			VideoButton.prototype.videoPoster = '/img/movie.png';
			VideoButton.prototype.needFocus = true;
			VideoButton.prototype.id = null;

			VideoButton.prototype._init = function() {
				this.title = this._t(this.name);
				//this.id = this.editor.opts.id || 'lang' + Math.round(Math.random() * (99999 - 1) + 1);
				this.id = this.editor.id;

				$.merge(this.editor.formatter._allowedTags, ['embed', 'iframe', 'video']);
				$.extend(this.editor.formatter._allowedAttributes, {
					embed: ['class', 'width', 'height', 'type', 'pluginspage', 'src', 'wmode', 'play', 'loop', 'menu', 'allowscriptaccess', 'allowfullscreen'],
					iframe: ['class', 'width', 'height', 'src', 'frameborder'],
					video: ['class', 'width', 'height', 'poster', 'controls', 'allowfullscreen', 'src', 'data-link', 'data-tag']
				});

				$(document).on('click', '.J_UploadVideoBtn_' + this.id , (function(_this) {
					return function(e) {
						var videoData;
						videoData = {
							link: $('.video-settings-' + _this.id + ' .video-link').val(),
							width: $('.video-settings-' + _this.id + ' .video-width').val() || 100,
							height: $('.video-settings-' + _this.id + ' .video-height').val() || 100
						};

						videoData.link = _this.parseVideo(videoData.link);
						
						$('.video-settings-' + _this.id + ' .video-link').val('');
						$('.video-settings-' + _this.id + ' .video-width').val('');
						$('.video-settings-' + _this.id + ' .video-height').val('');
						return _this.loadVideo($('.J_UploadVideoBtn_' + _this.id).data('videowrap'), videoData, function() {
							return _this.editor.trigger('valuechanged');
						});
					};
				})(this));

				this.editor.body.on('mouseenter', 'iframe.real-video', (function(_this) {
					return function(e) {
						return _this.popover.show($(e.currentTarget));
					};
				})(this));

				this.editor.body.on('mousedown', (function(_this) {
					return function() {
						return _this.popover.hide();
					};
				})(this));
				
				return VideoButton.__super__._init.call(this);
			};

			// - Supported YouTube URL formats:
			//   - http://www.youtube.com/watch?v=My2FRPA3Gf8
			//   - http://youtu.be/My2FRPA3Gf8
			//   - https://youtube.googleapis.com/v/My2FRPA3Gf8
			VideoButton.prototype.parseVideo = function(url){

				url.match(/(http:|https:|)\/\/(player.|www.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com))\/(video\/|embed\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/);
				var res = this.videoPoster;

				if (RegExp.$3.indexOf('youtu') > -1) {
					res = 'https://www.youtube.com/embed/' + RegExp.$6;
				}

				return res;
			}

			VideoButton.prototype.render = function() {
				var args;
				args = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
				VideoButton.__super__.render.apply(this, args);
				return this.popover = new VideoPopover({
					button: this
				});
			};

			VideoButton.prototype.renderMenu = function() {
				return VideoButton.__super__.renderMenu.call(this);
			};

			VideoButton.prototype._status = function() {
				return this._disableStatus();
			};

			VideoButton.prototype.loadVideo = function($video, videoData, callback) {
				var e, originNode, realVideo, videoLink, videoTag;
				if(!videoData.link)
				{
					$video.remove();
				}
				else
				{
					$video.attr({
						'src': videoData.link,
						'width': videoData.width || 100,
						'height': videoData.height || 100
					});
				}

				this.popover.hide();
				this.editor.trigger('valuechanged');
				this.editor.body.focus();

				return callback($video);
			};

			VideoButton.prototype.createVideo = function() {
				var $videoWrap, range;
				if (!this.editor.inputManager.focused) {
					this.editor.focus();
				}
				range = this.editor.selection.range();
				if (range) {
					range.deleteContents();
					this.editor.selection.range(range);
				}
				$videoWrap = $('<iframe/>').attr({
					'width': 100,
					'height': 100,
					'src': this.videoPoster,
					'data-link': '',
					'frameborder': 0,
					'allowfullscreen': '1',
					'class': 'real-video'
				});
				range.insertNode($videoWrap[0]);
				this.editor.selection.setRangeAfter($videoWrap, range);
				this.editor.trigger('valuechanged');
				return $videoWrap;
			};

			VideoButton.prototype.command = function() {
				var $video, _self;
				_self = this;
				$video = this.createVideo();
				return this.popover.show($video);
			};

			return VideoButton;

		})(Simditor.Button);

		VideoPopover = (function(_super) {
			__extends(VideoPopover, _super);

			function VideoPopover() {
				return VideoPopover.__super__.constructor.apply(this, arguments);
			}

			VideoPopover.prototype.offset = {
				top: 6,
				left: -4
			};

			VideoPopover.prototype.render = function() {
				var tpl;
				tpl = "<div class=\"video-settings video-settings-" + this.button.id + "\">\n<div class=\"settings-field\">\n" 
				+ "<label>" + (this._t('video')) + "</label>\n"
				+ "<input placeholder=\"" + (this._t('videoPlaceholder')) + "\" type=\"text\" class=\"video-link\">\n </div>\n" 
				+ "<div class=\"settings-field\">\n<label>" + (this._t('videoSize')) + "</label>\n" 
				+ "<input class=\"image-size video-size video-width\" type=\"text\" tabindex=\"2\" />\n<span class=\"times\">×</span>\n" 
				+ "<input class=\"image-size video-size video-height\" type=\"text\" tabindex=\"3\" />\n</div>\n" 
				+ "<div class=\"settings-field btn-row\"><button type=\"button\" class=\"video-btn ok\">ok</button><button type=\"button\" class=\"video-btn del\">delete</button></div>" 
				+ "<div class=\"video-upload\">\n<button type=\"button\" class=\"J_UploadVideoBtn btn J_UploadVideoBtn_" + this.button.id + "\">" + (this._t('uploadVideoBtn')) + "</div>\n</div>\n</div>";

				this.el.addClass('video-popover').append(tpl);
				this.srcEl = this.el.find('.video-link');
				this.widthEl = this.el.find('.video-width');
				this.heightEl = this.el.find('.video-height');
				this.okEl = this.el.find('.video-btn.ok');
				this.delEl = this.el.find('.video-btn.del');
				
				this.el.find('.video-size').on('keydown', (function(_this) {
					return function(e) {
						if (e.which === 13 || e.which === 27) {
							e.preventDefault();
							return $('.J_UploadVideoBtn_' + _this.button.id).click();
						}
					};
				})(this));

				this.srcEl.on('keydown', (function(_this) {
					return function(e) {
						if (e.which === 13 || e.which === 27) {
							e.preventDefault();
							return $('.J_UploadVideoBtn_' + _this.button.id).click();
						}
					};
				})(this));

				this.okEl.on('click', (function(_this) {
					return function(e) {
						e.preventDefault();
						return $('.J_UploadVideoBtn_' + _this.button.id).click();
					};
				})(this));

				this.delEl.on('click', (function(_this) {
					return function(e) {
						_this.target.remove();
						_this.hide();
						_this.editor.body.focus();
					};
				})(this));
			};

			VideoPopover.prototype.show = function() {
				var $video, $videoWrap, args, videoData;
				args = 1 <= arguments.length ? __slice.call(arguments, 0) : [];
				VideoPopover.__super__.show.apply(this, args);
				$video = arguments[0] || this.target;
				this.width = $video.attr('width') || $video.width();
				this.height = $video.attr('height') || $video.height();
				this.src = $video.attr('src');
				if($video.attr('src'))
				{
					videoData = {
						link: this.src,
						width: this.width,
						height: this.height
					};
				}
				this.widthEl.val(this.width);
				this.heightEl.val(this.height);
				this.srcEl.val(this.src);
				$('.J_UploadVideoBtn_' + this.button.id).data('videowrap', $video);
				return $videoWrap = this.target;
			};

			return VideoPopover;

		})(Simditor.Popover);
		return Simditor.Toolbar.addButton(VideoButton);
	});

}).call(this);
