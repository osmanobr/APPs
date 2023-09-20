
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
