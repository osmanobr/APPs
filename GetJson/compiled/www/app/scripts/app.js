﻿/*!
 This file is part of DecSoft App Builder.
 Visit our website for license information.
 Copyright © DecSoft Utils - All rights reserved.
 Visit our website at: https://www.decsoftutils.com
 */

 const DabComponents = {
  "dab-http": DabHttp, "dab-html": DabHtml, "dab-timer": DabTimer, "dab-audio": DabAudio, "dab-video": DabVideo, "dab-frame": DabFrame, "dab-label": DabLabel, "dab-table": DabTable, "dab-image": DabImage, "dab-iframe": DabIFrame, "dab-figure": DabFigure, "dab-navbar": DabNavbar, "dab-sidebar": DabSidebar, "dab-carousel": DabCarousel, "dab-dropdown": Dabdropdown, "dab-progress": DabProgress, "dab-websocket": DabWebsocket, "dab-container": DabContainer, "dab-text-input": DabTextInput, "dab-image-push": DabImagePush, "dab-file-input": DabFileInput, "dab-push-button": DabPushButton, "dab-range-input": DabRangeInput, "dab-radio-input": DabRadioInput, "dab-number-input": DabNumberInput, "dab-select-input": DabSelectInput, "dab-switch-input": DabSwitchInput, "dab-checkbox-input": DabCheckboxInput, "dab-textarea-input": DabTextareaInput, "dab-multi-select-input": DabMultiSelectInput 
};






const view1 = {
 components: DabComponents,
 created() {
   this.$root.views[this.name] = this;

 },
      data() {
        return {
          name: "view1",
          classes: "",
          transitionName: "",
          transitionMode: "",
          inAnimation: "",
          outAnimation: "",
          event: null,
          app: this.$root,
          button1: {
            name: "button1",
            classes: "",
            size: "sm",
            title: " Created with Unregistered DecSoft App Builder",
            tabIndex: 0,
            text: "Get a JSON response",
            kind: "primary",
            outline: false,
            active: false,
            leftIcon: "",
            rightIcon: "",
            leftBadge: "",
            leftBadgeKind: "light",
            leftBadgePilled: false,
            rightBadge: "",
            rightBadgeKind: "light",
            rightBadgePilled: false,
            hidden: false,
            disabled: false,
            event: null,
            blurHandler() {},
            focusHandler() {},

            clickHandler(event) {
              let
                view = app._getCurrentView(),
                views = app._getLoadedViews(),
                frames = app._getLoadedFrames(),
                dialogs = app._getLoadedDialogs(),
                self = views["view1"].button1;
                self.event = event;


// Just execute our HTTP call. (You can take a look at the HTTP control Done and Fail events)

views.view1.http1.execute();

            },
            dblclickHandler() {},
            mouseupHandler() {},
            mousedownHandler() {},
            mousemoveHandler() {},
            mouseenterHandler() {},
            mouseleaveHandler() {},
            contextmenuHandler() {}
          },

          http1: {
            name: "http1",
            data: {},
            headers: {},
            method: "GET",
            timeout: 0,
            userName: "",
            password: "",
            cache: false,
            contentType: "application/json",
            responseType: "",
            url: "Server/get_json.php",
            event: null,
            response: null,
            statusCode: null,
            textStatus: null,
            request: null,
            errorThrown: null,
            execute() {
              $.ajax({
                url: this.url,
                processData: false,
                cache: this.cache,
                method: this.method,
                headers: this.headers,
                timeout: this.timeout,
                username: this.userName,
                password: this.password,
                contentType: this.contentType,
                xhrFields: {
                  responseType: this.responseType
                },
                data: app._transformHttpRequest(this)
              })
              .fail(this.fail_handler)
              .done(this.done_handler);
            },
            setHeader(name, value) {
              this.headers[name] = value;
            },
            done_handler(response, textStatus, request) {
              let
                view = app._getCurrentView(),
                views = app._getLoadedViews(),
                frames = app._getLoadedFrames(),
                dialogs = app._getLoadedDialogs(),
                self = views["view1"].http1;
              self.response = response;
              self.textStatus = textStatus;
              self.statusCode = request.status;
              self.request = request;


// The server's response, as you can see in the "get_json.php" script
// included in this script, offer to us a JSON object, which we can
// directly access using the response property of the HTTP control.

app.showAlert(
  'O primeiro nome é: ' +
  views.view1.http1.response.firstName +
  ' o sobrenome é: ' +
  views.view1.http1.response.lastName
);
            },
            fail_handler(request, textStatus, errorThrown) {
              let
                view = app._getCurrentView(),
                views = app._getLoadedViews(),
                frames = app._getLoadedFrames(),
                dialogs = app._getLoadedDialogs(),
                self = views["view1"].http1;
              self.request = request;
              self.textStatus = textStatus;
              self.errorThrown = errorThrown;
              self.statusCode = request.status;


            }
          }
        };
      },
      activated() {
        let
          view = this,
          self = this,
          views = app._getLoadedViews(),
          frames = app._getLoadedFrames(),
          dialogs = app._getLoadedDialogs();
        view.event = null;
          app._setViewEvents(this);

      },
      deactivated() {
        let
          view = this,
          self = this,
          views = app._getLoadedViews(),
          frames = app._getLoadedFrames(),
          dialogs = app._getLoadedDialogs();
        view.event = null;

      },
      methods: {
        clickHandler(event) {
          let
            view = this,
            self = this,
            views = app._getLoadedViews(),
            frames = app._getLoadedFrames(),
            dialogs = app._getLoadedDialogs();
          view.event = event;

        },
        dblclickHandler(event) {
          let
            view = this,
            self = this,
            views = app._getLoadedViews(),
            frames = app._getLoadedFrames(),
            dialogs = app._getLoadedDialogs();
          view.event = event;

        },
        mouseupHandler(event) {
          let
            view = this,
            self = this,
            views = app._getLoadedViews(),
            frames = app._getLoadedFrames(),
            dialogs = app._getLoadedDialogs();
          view.event = event;

        },
        mousedownHandler(event) {
          let
            view = this,
            self = this,
            views = app._getLoadedViews(),
            frames = app._getLoadedFrames(),
            dialogs = app._getLoadedDialogs();
          view.event = event;

        },
        mousemoveHandler(event) {
          let
            view = this,
            self = this,
            views = app._getLoadedViews(),
            frames = app._getLoadedFrames(),
            dialogs = app._getLoadedDialogs();
          view.event = event;

        },
        mouseenterHandler(event) {
          let
            view = this,
            self = this,
            views = app._getLoadedViews(),
            frames = app._getLoadedFrames(),
            dialogs = app._getLoadedDialogs();
          view.event = event;

        },
        mouseleaveHandler(event) {
          let
            view = this,
            self = this,
            views = app._getLoadedViews(),
            frames = app._getLoadedFrames(),
            dialogs = app._getLoadedDialogs();
          view.event = event;

        },
        contextmenuHandler(event) {
          let
            view = this,
            self = this,
            views = app._getLoadedViews(),
            frames = app._getLoadedFrames(),
            dialogs = app._getLoadedDialogs();
          view.event = event;

        },
        swipeRightHandler(event) {
          let
            view = this,
            self = this,
            views = app._getLoadedViews(),
            frames = app._getLoadedFrames(),
            dialogs = app._getLoadedDialogs();
          view.event = event;

        },
        swipeLeftHandler(event) {
          let
            view = this,
            self = this,
            views = app._getLoadedViews(),
            frames = app._getLoadedFrames(),
            dialogs = app._getLoadedDialogs();
          view.event = event;

        }
    },
      template: `<!-- (Unregistered DecSoft App Builder - https://www.decsoftutils.com/) -->
<transition v-bind:[app.viewTransitionName]="transitionName" v-bind:[app.viewTransitionMode]="transitionMode" v-bind:[app.viewInAnimation]="'animate__animated ' + 'animate__' + inAnimation" v-bind:[app.viewOutAnimation]="'animate__animated ' + 'animate__' +  outAnimation"><div title="Created with Unregistered DecSoft App Builder" id="view1" v-bind:class="['app-view', classes]"><dab-sidebar v-bind="app.sidebar"></dab-sidebar><dab-push-button v-bind="button1"></dab-push-button><dab-http v-bind="http1"></dab-http></div></transition>
<!-- (Unregistered DecSoft App Builder - https://www.decsoftutils.com/) -->`
  };

const DabApp = {

  components: {
    "dab-alert": DabAlert,
    "dab-toast": DabToast,
    view1,
    
  },

  created() {

    window.app = this;
    this._setupAppHtml();
    this._setupAppEvents();
    this._setupAppVariables();
    this._setupAppPlugins();
    this._setupCordovaPlugins();
    
  },

  mounted() {
    
  },

  data() {

    return {
      store: {},
      views: [],
      event: null,
      error: null,
      
      id: "com.decsoft.getjson",
      version: "1.0.0",
      name: "GetJson",
      shortName: "GetJson",
      description: "Another DecSoft App Builder app",
      authorName: "DecSoft App Builder",
      authorEmail: "DecSoft App Builder",
      authorUrl: "https://www.decsoftutils.com/",
      language: "en",
      languageName: "English",
      textDirection: "ltr",
      style: "scaled",
      buildNumber: 0,
      lastSound: null,
      activeDialog: null,
      defaultLanguage: "en",
      theme: "default",
      themes: ["default"],
      sidebar: {
        item: {
          index: -1,
          subindex: -1
        },
        header: "",
        direction: "left",
        headerKind: "light",
        headerAlign: "left",
        imageUrl: "",
        items: [],
        itemClickHandler: this._appEventOnSidebarItemClick,
        headerClickHandler: this._appEventOnSidebarHeaderClick
      },
    };
  },

  computed: {

    viewTransitionName() {

      return this._getCurrentView().transitionName !== '' ? 'name' : null;
    },

    viewTransitionMode() {

      return this._getCurrentView().transitionMode !== '' ? 'mode' : null;
    },

    viewInAnimation() {

      return this._getCurrentView().inAnimation !== '' ? 'enter-active-class' : null;
    },

    viewOutAnimation() {

      return this._getCurrentView().outAnimation !== '' ? 'leave-active-class' : null;
    }
  },

  methods: {

    // Public app variables related methods

    setVar(name, value) {

      this.store[name] = value;
    },

    // Public app views related methods

    showView(viewName) {

      this.$router.push(viewName);
    },

    replaceView(viewName) {

      this.$router.replace(viewName);
    },

    // Public app dialogs related methods

    showDialog(dialogName, shownCallback, hiddenCallback) {

      let modalEl = document.getElementById(dialogName);
      let modal = new bootstrap.Modal(modalEl, { keyboard: false, backdrop: 'static' });
      modal.show();

      if (typeof shownCallback === 'function') {
        modalEl.addEventListener('shown.bs.modal', shownCallback, { once: true });
      }
      if (typeof hiddenCallback === 'function') {
        modalEl.addEventListener('hidden.bs.modal', hiddenCallback, { once: true });
      }
    },

    hideDialog(dialogName) {

      let elem = window.document.querySelector(`#${dialogName}.app-dialog.modal.show`);
      if (elem !== null) {
        let dialog = bootstrap.Modal.getInstance(elem);
        if (dialog !== null) {
          dialog.hide();
        }
      }
    },

    hideDialogs() {

      let dialogs = window.document.querySelectorAll('.app-dialog.modal.show');
      for (let i = 0; i < dialogs.length; i++) {
        let dialog = bootstrap.Modal.getInstance(dialogs[i]);
        if (dialog !== null) {
          dialog.hide();
        }
      }
    },

    getVisibleDialogs() {

      let dialogs = [];
      window.document.querySelectorAll('.app-dialog.modal.show').forEach(function (el) {
        dialogs.push(el.id);
      });
      return dialogs;
    },

    isDialogVisible(dialogName) {

      return window.document.querySelector(`#${dialogName}.app-dialog.modal.show`) !== null;
    },

    // Public app alert related methods

    showAlert(body = '', title = '', kind = '', buttons = [], closeCallback = () => {}) {

      let alert = this.$refs.DabAlert;

      alert.body = body;
      alert.title = title;
      alert.kind = kind;
      alert.buttons = buttons;
      alert.closeCallback = closeCallback;

      let modal = new bootstrap.Modal(window.document.getElementById('dab-alert-modal'), { keyboard: false, backdrop: 'static' });
      let elem = window.document.getElementById('dab-alert-modal');

      elem.addEventListener('hidePrevented.bs.modal', () => {
        if (!alert.buttons || alert.buttons.length === 0) {
          modal.hide();
        }
      }, { once: true });

      modal.show();
    },

    hideAlert() {

      let elem = window.document.querySelector('#dab-alert-modal.modal.show');
      if (elem !== null) {
        let modal = bootstrap.Modal.getInstance(elem);
        if (modal !== null) {
          modal.hide();
        }
      }
    },

    // Public app sidebar related methods

    showSidebar() {

      $('#dab-sidebar').addClass('active');
      $('#dab-sidebar-overlay').addClass('active');
    },

    hideSidebar() {

      $('#dab-sidebar').removeClass('active');
      $('#dab-sidebar-overlay').removeClass('active');
    },

    sidebarIsVisible() {

      return $('#dab-sidebar').hasClass('active');
    },

    sidebarSetDirection(direction) {

      if (this.sidebar.direction === direction) {
        return this.sidebar.direction;
      }
      this.sidebar.direction = direction;
      if (direction === 'right') {
        let
          css = '#dab-sidebar { left: initial; right: -280px; } #dab-sidebar.active { left: initial; right: 0; }',
          style = document.createElement('style');
        document.head.appendChild(style);
        style.id = 'sidebar-stylesheet';
        style.appendChild(document.createTextNode(css));
      } else {
        if ($('#sidebar-stylesheet').length > 0) {
          $('#sidebar-stylesheet').remove();
        }
      }
      return this.sidebar.direction;
    },

    // Public app toasts related methods

    showToast(text = '', hideMsecs = false, kind = 'light', title = '', subtitle = '', clickCallback = () => {}, dismissCallback = () => {}, payload = null) {

      let
        toastId = this.randomStr(),
        toasts = this._getToastsComponent();

      toasts.toasts.push({
        "text": text,
        "id": toastId,
        "title": title,
        "kind": kind,
        "payload": payload,
        "subtitle": subtitle,
        "clickCallback": clickCallback,
        "dismissCallback": dismissCallback
      });

      if (hideMsecs) {
        window.setTimeout(() => {
          for (let i in toasts.toasts) {
            if (toasts.toasts[i].id === toastId) {
              toasts.toasts.splice(i, 1);
            }
          }
        }, hideMsecs);
      }
      return toastId;
    },

    hideToast(toastId) {

      let toasts = this._getToastsComponent();
      for (let i in toasts.toasts) {
        if (toasts.toasts[i].id === toastId) {
          toasts.toasts.splice(i, 1);
        }
      }
    },

    hideToasts() {

      let toasts = this._getToastsComponent();
      toasts.toasts = [];
    },

    getToast(toastId) {

      let toasts = this._getToastsComponent();
      for (let i in toasts.toasts) {
        if (toasts.toasts[i].id === toastId) {
          return toasts.toasts[i];
        }
      }
      return null;
    },

    getToasts() {

      let toasts = this._getToastsComponent();
      return toasts.toasts;
    },

    // Public app local storage related methods

    setOption(key, value) {

      return localStorage.setItem(key, value);
    },

    getOption(key, defaultValue) {

      return localStorage.getItem(key) !== null ?
        localStorage.getItem(key) : defaultValue;
    },

    removeOption(key) {

      return localStorage.removeItem(key);
    },

    clearOptions() {

      return localStorage.clear();
    },

    // Public app controls related methods

    focusControl(controlName) {

      $('#' + controlName).focus();
    },

    // Public app themes related methods

    setAppTheme(themeName) {

      let lowerThemeName = themeName.toLowerCase();

      if (this.themes.indexOf(lowerThemeName) === -1) {
        return false;
      }

      $('body').removeClass(this.theme);
      this.theme = lowerThemeName;
      $('#app-theme').attr('href', 'app/styles/' + lowerThemeName + '.css');
      $('body').addClass(this.theme);
      return lowerThemeName;
    },

	  getAppThemeColor() {

      return $('html').attr('data-bs-theme');
	  },

	  setAppThemeColor(colorMode) {

      if (colorMode === 'light' || colorMode === 'dark') {
        $('html').attr('data-bs-theme', colorMode);
      }
	  },

    setAppFixedStyle() {

      this.style = 'fixed';
      $('#app-style').attr('href', 'app/styles/fixed.css');
    },

    setAppScaledStyle() {

      this.style = 'scaled';
      $('#app-style').attr('href', 'app/styles/scaled.css');
    },

    setAppTextDirection(textDirection) {

      let html = document.getElementsByTagName('html')[0];
      this.textDirection = textDirection;
      html.setAttribute('dir', textDirection);
    },

    getAppColorScheme() {

      return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    },

    // Public app device related methods

    setViewReadyForDeviceKeyboard() {

      let self = this;

      document.addEventListener('deviceready', () => {
        $('#' + self._getCurrentView().name).css({
          "margin-top": '0px',
          "height": window.innerHeight + 'px'
        });
      });
    },

    setDialogReadyForDeviceKeyboard() {

      let self = this;

      document.addEventListener('deviceready', () => {
        $('#' + self._getCurrentView().name + ' .modal-content').css({
          "margin-top": '0px',
          "height": window.innerHeight + 'px'
        });
      });
    },

    // Public app strings related methods

    strLen(text) {

      return text.length;
    },

    trimStr(text) {

      return text.trim();
    },

    lowerCase(text) {

      return text.toLowerCase();
    },

    upperCase(text) {

      return text.toUpperCase();
    },

    strSearch(text, query) {

      return text.search(query);
    },

    subStr(text, start, count) {

      return text.substr(start, count);
    },

    strReplace(text, from, to) {

      return text.replace(from, to);
    },

    strReplaceAll(text, from, to) {

      return text.split(from).join(to);
    },

    splitStr(text, separator, limit) {

      return text.split(separator, limit);
    },

    strToBase64(text) {

      return window.Base64.encode(text);
    },

    base64ToStr(text) {

      return window.Base64.decode(text);
    },

    serialize(value) {

      return JSON.stringify(value);
    },

    unserialize(text) {

      return JSON.parse(text);
    },

    randomStr(length = 10) {

      let
        result = '',
        charsMap = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789',
        mapLength = charsMap.length;

      for (let i = 0; i < length; i++) {
        result += charsMap.charAt(Math.floor(Math.random() * mapLength));
      }

      return result;
    },

    formatStr (str) {

      let args = Array.prototype.slice.call(arguments, 1);

      return str.replace(/{(\d+)}/g, function(match, number) {

        return typeof args[number] !== 'undefined'
          ? args[number]
          : match
        ;
      });
    },

    // Public app numbers related methods

    randomNum(maxNum) {

      maxNum = maxNum || 100;
      return Math.floor(Math.random() * maxNum);
    },


    // Public app sound related methods

    beep() {

      this.playSound(
        'app/audios/beep/beep.mp3',
        'app/audios/beep/beep.ogg'
      );
    },

    playSound(mp3Url, oggUrl) {

      if (this.lastSound === null) {
        this.lastSound = new Audio();
      }

      if (this.lastSound.canPlayType('audio/ogg') !== '') {
        this.lastSound.src = oggUrl;
        this.lastSound.type = 'audio/ogg';
      } else {
        this.lastSound.src = mp3Url;
        this.lastSound.type = 'audio/mpeg';
      }

      this.lastSound.play();
      return this.lastSound;
    },

    stopSound() {

      this.lastSound.pause();
      this.lastSound.currentTime = 0;
    },

    // Public app resources related methods

    resource(name) {

      if (!appLangs[this.language]) {
        return appLangs[this.defaultLanguage].resources[name];
      } else {
        return appLangs[this.language]['resources'][name] !== undefined ?
          appLangs[this.language]['resources'][name] :
          appLangs[this.defaultLanguage].resources[name];
      }
    },

    // Public app languages related methods

    translateView() {

      return this._translateView();
    },

    getLanguages() {

      let result = [];
      for (let i in appLangs) {
        result.push({ code: i, name: appLangs[i].language.name });
      }
      return result;
    },

    getLanguagesNames() {

      let result = [];
      for (let i in appLangs) {
        result.push(appLangs[i].language.name);
      }
      return result;
    },

    getLanguagesCodes() {

      let result = [];
      for (let i in appLangs) {
        result.push(i);
      }
      return result;
    },

    getLanguageCodeFromName(name) {

      let result = '';
      for (var i in appLangs) {
        if (appLangs[i].language.name === name) {
          result = i;
          break;
        }
      }
      return result;
    },

    getLanguageNameFromCode(code) {

      let result = '';
      for (let i in appLangs) {
        if (i === code) {
          result = appLangs[i].language.name;
          break;
        }
      }
      return result;
    },

    

    // Internal / Private app methods

    _appEventOnResize(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = event;
      
    },

    _appEventOnMessage(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = event;
      
    },

    _appEventOnContextMenu(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = event;
      
    },

    _appEventOnColorSchemeChange(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = event;

      
    },

    _appEventOnOnline(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = event;
      
    },

    _appEventOnOffline(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = event;
      
    },

    _appEventOnDomReady() {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = null;
      
    },

    _appEventOnBatteryStatus(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = null;
      self.cordova.battery.level = event.level;
      self.cordova.battery.isPlugged = event.isPlugged;
      
    },

    _appEventOnBatteryLow(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = null;
      self.cordova.battery.level = event.level;
      self.cordova.battery.isPlugged = event.isPlugged;
      
    },

    _appEventOnBatteryCritical(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = null;
      self.cordova.battery.level = event.level;
      self.cordova.battery.isPlugged = event.isPlugged;
      
    },

    _appEventOnDeviceReady(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = event;

      window.addEventListener('online', self._appEventOnOnline);
      window.addEventListener('offline', self._appEventOnOffline);
      window.addEventListener('batterylow', self._appEventOnBatteryLow);
      window.addEventListener('batterystatus', self._appEventOnBatteryStatus);
      window.addEventListener('batterycritical', self._appEventOnBatteryCritical);
      window.addEventListener('orientationchange', self._appEventOnOrientationChange);
      document.addEventListener('pause', self._appEventOnPause);
      document.addEventListener('resume', self._appEventOnResume);
      
      
      
      
    },

    _appEventOnPause(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = event;
      
    },

    _appEventOnResume(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = event;
      
    },

    _appEventOnBackButton(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = event;
      
    },

    _appEventOnOrientationChange(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = event;
      
    },

    _appEventOnVolumeUpButton(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = event;
      
    },

    _appEventOnVolumeDownButton(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = event;
      
    },

    _appEventOnClick(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = event;
      
    },

    _appEventOnDblClick(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = event;
      
    },

    _appEventSwipeRight(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = event;
      
    },

    _appEventSwipeLeft(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = event;
      
    },

    _appEventOnKeyUp(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = event;
      
    },

    _appEventOnKeyDown(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = event;
      
    },

    _appEventOnMouseUp(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = event;
      
    },

    _appEventOnMouseDown(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = event;
      
    },

    _appEventOnMouseMove(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = event;
      
    },

    _appEventOnRenderError(error, instance, info) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = null;
      self.error = { "error": error, "instance": instance, "info": info };

      
      self.error = null;
    },

    _appEventOnJavascriptError(errorMsg, url, lineNumber) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = null;
      self.error = { "message": errorMsg, "url": url, "lineNumber": lineNumber };

      
      self.error = null;
    },

    _appEventViewChange(to, from) {

      if (to.name === from.name) {
        return;
      }

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();

      self.event = {
        "nextViewName": to.name,
        "prevViewName": from.name
      };

      
    },

    _appEventOnSidebarItemClick(event, item, index, subindex) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = event;
      self.sidebar.item = item;
      self.sidebar.item.index = index;
      self.sidebar.item.subindex = subindex;
      
    },

    _appEventOnSidebarHeaderClick(event) {

      let
        self = this,
        view = this._getCurrentView(),
        views = this._getLoadedViews(),
        frames = this._getLoadedFrames(),
        dialogs = this._getLoadedDialogs();
      self.event = event;
      
    },

    _setupAppEvents() {

      this._setupAppErrorsEvents();
      this._setupAppWindowEvents();
      this._setupAppDocumentEvents();
      this._setupAppHammerEvents();
      this._setupAppDeviceEvents();
      this._setupAppRouterEvents();
      this._setupAppChromeEvents();
    },

    _setupAppErrorsEvents() {

      appInstance.config.errorHandler = this._appEventOnRenderError;
      window.addEventListener('error', this._appEventOnJavascriptError);
    },

    _setupAppWindowEvents() {

      window.addEventListener('resize', this._appEventOnResize);
      window.addEventListener('message', this._appEventOnMessage);
      window.addEventListener('contextmenu', this._appEventOnContextMenu);
      window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', this._appEventOnColorSchemeChange);
    },

    _setupAppDocumentEvents() {

      let self = this;
      $(self._appEventOnDomReady);
      document.addEventListener('click', this._appEventOnClick);
      document.addEventListener('dblclick', this._appEventOnDblClick);
      document.addEventListener('keyup', this._appEventOnKeyUp);
      document.addEventListener('keydown', this._appEventOnKeyDown);
      document.addEventListener('mouseup', this._appEventOnMouseUp);
      document.addEventListener('mousedown', this._appEventOnMouseDown);
      document.addEventListener('mousemove', this._appEventOnMouseMove);
    },

    _setupAppHammerEvents() {

      let ha = new Hammer(document.body);
      ha.get('swipe').set({ direction: Hammer.DIRECTION_HORIZONTAL });
      ha.on('swiperight', this._appEventSwipeRight);
      ha.on('swipeleft', this._appEventSwipeLeft);
    },

    _setupAppDeviceEvents() {

      document.addEventListener('deviceready', this._appEventOnDeviceReady);
    },

    _setupAppRouterEvents() {

      this.$router.beforeEach((to, from) => {
        if (from.name === undefined) {
          // The first time the app is running
          return true;
        }
        return this._appEventViewChange(to, from);
      });
    },

    _setupAppChromeEvents() {

      if (window.chrome && chrome.runtime && chrome.runtime.onMessage) {
        chrome.runtime.onMessage.addListener(
          this._appEventOnChromeRuntimeMessage);
      }
    },

    _getCurrentView() {

      if (this.activeDialog !== null) {
        return this.activeDialog;
      }

      for (let view in this.views) {
        if (this.$router.currentRoute.value.name === view) {
          return this.views[view];
        }
      }
      return null;
    },

    _getLoadedViews() {

      return this.views;
    },

    _getLoadedFrames() {

      return {
        
      };
    },

    _getLoadedDialogs() {

      return this.$refs;
    },

    _getActiveDialog() {

      this.activeDialog = null;

      let
        activeModals = $($('.modal.show').get().reverse());

      if (activeModals.length > 0) {

        for (let i = 0; i < this.$refs.length; i++) {
          if (this.$ref[i].$el.id === $($('.modal.show').get().reverse())[0].id) {
            this.activeDialog = this.$refs[i];
            break;
          }
        }
      }

      return this.activeDialog;
    },

    _getToastsComponent() {

      for (let i in this.$refs) {
        if (this.$refs[i].$el.id === 'dab-toasts-wrapper') {
          return this.$refs[i];
        }
      }
      return null;
    },

    _transformHttpRequest(http) {

      if (http.contentType === 'application/json') {

        return JSON.stringify(http.data);

      } else if (http.contentType === 'application/x-www-form-urlencoded') {

        let result = [];
        for (let key in http.data) {
          result.push(encodeURIComponent(key) + '=' + encodeURIComponent(http.data[key]));
        }

        return result.join('&');

      } else if (http.contentType === false || http.contentType === 'multipart/form-data') {

        let result = new FormData();
        for (let key in http.data) {
          result.append(key, http.data[key]);
        }

        return result;

      } else {

        return http.data;
      }
    },

    _translateFrames() {

      let frames = this._getLoadedFrames();
      for (let frame in frames) {
        for (let control in frames[frame]) {
          for (let property in frames[frame][control]) {
            let origProp = property + '_default';
            if (this.language === this.defaultLanguage) {
              if (frames[frame][control][origProp] !== undefined) {
                frames[frame][control][property] = frames[frame][control][origProp];
              }
              continue;
            }
            if (appLangs[this.language] && appLangs[this.language]['frames'] &&
              appLangs[this.language]['frames'][frame] && appLangs[this.language]['frames'][frame][control] &&
              appLangs[this.language]['frames'][frame][control][property]) {
               if (frames[frame][control][origProp] === undefined) {
                 frames[frame][control][origProp] = frames[frame][control][property];
               }
               frames[frame][control][property] = appLangs[this.language]['frames'][frame][control][property];
            }
          }
        }
      }
    },

    _translateSidebar() {

      if (appLangs[this.language] && appLangs[this.language].sidebar) {
        if (appLangs[this.language].sidebar['header'] !== undefined) {
          this.sidebar.header = appLangs[this.language].sidebar.header;
        }
        if (appLangs[this.language].sidebar['imageUrl'] !== undefined) {
          this.sidebar.imageUrl = appLangs[this.language].sidebar.imageUrl;
        }
        if (appLangs[this.language].sidebar['items'] !== undefined) {
          this.sidebar.items = appLangs[this.language].sidebar.items;
        }
      }
    },

    _translateView() {

      let view = this._getCurrentView();

      this._translateFrames();
      this._translateSidebar();

      for (let control in view.$data) {

        for (let property in view[control]) {
          let
            origProp = property + '_default';
          if (this.language === this.defaultLanguage) {
            if (view[control][origProp] !== undefined) {
              view[control][property] = view[control][origProp];
            }
            continue;
          }
          if (appLangs[this.language] && appLangs[this.language]['views'] &&
            appLangs[this.language]['views'][view.name] && appLangs[this.language]['views'][view.name][control] &&
            appLangs[this.language]['views'][view.name][control][property]) {
            if (view[control][origProp] === undefined) {
              view[control][origProp] = view[control][property];
            }
            view[control][property] = appLangs[this.language]['views'][view.name][control][property];
          }
        }
      }
    },

    _setViewEvents(view) {

      if (view._with_event_listeners) {
        return true;
      }

      let
        el = document.getElementById(view.name),
        ha = new Hammer(el);
      el.addEventListener('click', view.clickHandler);
      el.addEventListener('dblclick', view.dblclickHandler);
      el.addEventListener('mouseup', view.mouseupHandler);
      el.addEventListener('mousedown', view.mousedownHandler);
      el.addEventListener('mousemove', view.mousemoveHandler);
      el.addEventListener('mouseenter', view.mouseenterHandler);
      el.addEventListener('mouseleave', view.mouseleaveHandler);
      el.addEventListener('contextmenu', view.contextmenuHandler);
      ha.get('swipe').set({ direction: Hammer.DIRECTION_HORIZONTAL });
      ha.on('swiperight', view.swipeRightHandler);
      ha.on('swipeleft', view.swipeLeftHandler);
      view._with_event_listeners = true;
    },

    _setupAppHtml() {

      let
        html = document.getElementsByTagName('html')[0],
        body = document.getElementsByTagName('body')[0],
        title = document.getElementsByTagName('title')[0];
      title.innerText = this.name;
      body.classList.add(this.theme);
      html.setAttribute('dir', this.textDirection);
      html.setAttribute('lang', this.language);
    },

    _setupAppVariables() {

      this.query = false;
      this.navbarStyle = { dark: 'dark', light: 'light' };
      this.size = { sm: 'sm', md: 'md', lg: 'lg', xl: 'xl' };
      this.align = { left: "left", center: "center", right: "right" };
      this.themeColor = { dark: 'dark', light: 'light' };
      this.dropdownItem = { item: 'item', header: 'header', divider: 'divider' };
      this.dropdownDir = { up: 'dropup', down: 'dropdown', right: 'dropright' };
      this.httpMethod = { get: 'GET', post: 'POST', put: 'PUT', head: 'HEAD', "delete": 'DELETE' };
      this.navbarPos = { fixedTop: 'fixed-top', fixedBottom: 'fixed-bottom', stickyTop: 'sticky-top' };
      this.imageFit = { fill: 'fill', none: 'none', cover: 'cover', contain: 'contain', scale: 'scale' };
      this.kind = { link: 'link', dark: 'dark', info: 'info', light: 'light', danger: 'danger', primary: 'primary', warning: 'warning', success: 'success', secondary: 'secondary' };
      this.dialogFullscreen = { all: 'fullscreen', none: 'none', sm: 'fullscreen-sm-down', md: 'fullscreen-md-down', lg: 'fullscreen-lg-down', xl: 'fullscreen-xl-down', xxl: 'fullscreen-xxl-down' };
      this.inAnimation = { bounceIn: 'bounceIn', bounceInDown: 'bounceInDown', bounceInLeft: 'bounceInLeft', bounceInRight: 'bounceInRight', bounceInUp: 'bounceInUp', fadeIn: 'fadeIn', fadeInDown: 'fadeInDown', fadeInDownBig: 'fadeInDownBig', fadeInLeft: 'fadeInLeft', fadeInLeftBig: 'fadeInLeftBig', fadeInRight: 'fadeInRight', fadeInRightBig: 'fadeInRightBig', fadeInUp: 'fadeInUp', fadeInUpBig: 'fadeInUpBig', fadeInTopLeft: 'fadeInTopLeft', fadeInTopRight: 'fadeInTopRight', fadeInBottomLeft: 'fadeInBottomLeft', fadeInBottomRight: 'fadeInBottomRight', flipInX: 'flipInX', flipInY: 'flipInY', lightSpeedIn: 'lightSpeedIn', lightSpeedInRight: 'lightSpeedInRight', lightSpeedInLeft: 'lightSpeedInLeft', rollIn: 'rollIn', rotateIn: 'rotateIn', rotateInDownLeft: 'rotateInDownLeft', rotateInDownRight: 'rotateInDownRight', rotateInUpLeft: 'rotateInUpLeft', rotateInUpRight: 'rotateInUpRight', zoomIn: 'zoomIn', zoomInDown: 'zoomInDown', zoomInLeft: 'zoomInLeft', zoomInRight: 'zoomInRight', zoomInUp: 'zoomInUp', bounce: 'bounce', flash: 'flash', pulse: 'pulse', rubberBand: 'rubberBand', shake: 'shake', swing: 'swing', tada: 'tada', wobble: 'wobble', jello: 'jello' };
      this.outAnimation = { bounceOut: 'bounceOut', bounceOutDown: 'bounceOutDown', bounceOutLeft: 'bounceOutLeft', bounceOutRight: 'bounceOutRight', bounceOutUp: 'bounceOutUp', fadeOut: 'fadeOut', fadeOutDown: 'fadeOutDown', fadeOutDownBig: 'fadeOutDownBig', fadeOutLeft: 'fadeOutLeft', fadeOutLeftBig: 'fadeOutLeftBig', fadeOutRight: 'fadeOutRight', fadeOutRightBig: 'fadeOutRightBig', fadeOutUp: 'fadeOutUp', fadeOutUpBig: 'fadeOutUpBig', fadeOutTopLeft: 'fadeOutTopLeft', fadeOutTopRight: 'fadeOutTopRight', fadeOutBottomRight: 'fadeOutBottomRight', fadeOutBottomLeft: 'fadeOutBottomLeft', flipOutX: 'flipOutX', flipOutY: 'flipOutY', lightSpeedOut: 'lightSpeedOut', lightSpeedOutRight: 'lightSpeedOutRight', lightSpeedOutLeft: 'lightSpeedOutLeft', rollOut: 'rollOut', rotateOut: 'rotateOut', rotateOutDownLeft: 'rotateOutDownLeft', rotateOutDownRight: 'rotateOutDownRight', rotateOutUpLeft: 'rotateOutUpLeft', rotateOutUpRight: 'rotateOutUpRight', slideOutUp: 'slideOutUp', slideOutDown: 'slideOutDown', slideOutLeft: 'slideOutLeft', slideOutRight: 'slideOutRight', zoomOut: 'zoomOut', zoomOutDown: 'zoomOutDown', zoomOutLeft: 'zoomOutLeft', zoomOutRight: 'zoomOutRight', zoomOutUp: 'zoomOutUp', backOutDown: 'backOutDown', backOutLeft: 'backOutLeft', backOutRight: 'backOutRight', backOutUp: 'backOutUp', bounce: 'bounce', flash: 'flash', pulse: 'pulse', rubberBand: 'rubberBand', shake: 'shake', swing: 'swing', tada: 'tada', wobble: 'wobble', jello: 'jello', hinge: 'hinge' };
    },

    _setupAppPlugins() {

      this.plugins = {};
      
    },

    _setupCordovaPlugins() {

      this.cordova = {};
      
    }
  }
};

const router = VueRouter.createRouter({
  history: VueRouter.createWebHashHistory(),
  routes: [
    
    { path: '/', redirect: '/view1' },
    { path: '/:pathMatch(.*)*', redirect: '/view1' },
    { path: '/view1', component: view1, name: 'view1', view1, props(route) { app.query = $.isEmptyObject(route.query) ? false : route.query; } }
  ]
});

const pinia = Pinia.createPinia();

const appInstance = Vue.createApp(DabApp).use(pinia).use(router);

appInstance.mount('#app');
