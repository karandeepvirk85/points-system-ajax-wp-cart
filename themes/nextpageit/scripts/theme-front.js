var objTheme = {
  elementLinks: "bottom-links",
  elementMenuFaIcon: "menu-fa-icon",
  classOpenMenu: "open-menu",
  classRotate: "rotate",
  MobileMenuOpenClose: function () {
    var elementLinks = document.getElementById(this.elementLinks);
    var elementMenuFaIcon = document.getElementById(this.elementMenuFaIcon);
    if (!elementLinks.classList.contains(this.classOpenMenu)) {
      elementLinks.classList.add(this.classOpenMenu);
      elementMenuFaIcon.classList.add(this.classRotate);
    } else {
      elementLinks.classList.remove(this.classOpenMenu);
      elementMenuFaIcon.classList.remove(this.classRotate);
    }
  },

  openTab: function (evt, tabName) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
      tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
      tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";
  },
};

$(document).ready(function () {
  var objCart = {
    buttonAddToCart: $(".add-to-cart"),
    buttonCheckout: $(".checkout-and-reedem"),
    removeItemButton: $(".remove-item-from-cart"),
    inputQtyChange: $(".cart-qty-change"),
    elementProductContainer: $(".product-main-container"),
    elementUpdatePoints: $("#user-total-points"),
    elementCartBottom: $(".cart-bottom-part"),
    elementCartBottomAnimation: $(".checkout-bottom-part-animation"),
    elementUserInfoContainer: $(".account-info-main"),
    apiUpdatePoints: "https://survey-api.npit.at/api/PointsHistory/Save",
    apiMeToGetData: "https://survey-api.npit.at/api/User/Me",
    elementAfterAjaxMessage: $(".after-ajax-call-message"),
    elementProductQuantity: $(".product-quantity"),
    elementUserMetaInfo: $(".user-meta-information"),
    elementShowPoints: $("#points-on-selection"),
    elementUserSpinner: $(".user-meta-spinner"),
    elementUserName: $(".user-meta-name"),
    elementUserPoints: $(".user-meta-points"),
    elementCartTable: $("#cart-table"),
    elementCartMetaUpdateProducts: $("#cart-meta-products"),
    elementCartMetaUpdatePoints: $("#cart-meta-points"),
    elementShowUserInfo: $(".account-info-container-inner"),
    cookieName: "token",
    elementCartAndMeta: $(".cart-and-meta-container"),
    elementSortProducts: $("#sort-products"),

    /**
     * Init Function
     */
    initCart: function () {
      /**
       * Event: Onload
       * Action : Open Single Shop Default Tab
       */
      var defaultOpen = document.getElementById("defaultOpen");
      if (defaultOpen) {
        defaultOpen.click();
      }

      /**
       * Event: OnLoad
       * Action: Get Data From API
       */
      this.getDataFromApi(this.getTokenFromCookie(this.cookieName));

      /**
       * Event: Change
       * Action: When user hit add to cat button this action triggers
       * Type: Event
       */
      this.buttonAddToCart.click(function () {
        objCart.getData(this);
      });

      /**
       * Event: Click
       * Action: When user click on products take him to single product page
       * Type: Event
       */
      if (this.elementProductContainer.length) {
        objCart.elementProductContainer.click(function () {
          objCart.goToSingleView(this);
        });
      }

      /**
       * Event: onChange
       * Action: Submit Sorting Form on Change
       * Type: Event
       */
      if (this.elementSortProducts.length) {
        this.elementSortProducts.change(function () {
          objCart.submitSortForm(this);
        });
      }

      /**
       * Event: Click
       * Action: Checkout User
       * Type: Event
       */
      this.buttonCheckout.click(function () {
        objCart.checkOutUser();
      });

      /**
       * Event: Click
       * Action: Remove Item from cart
       * Type: Event
       */
      this.removeItemButton.click(function () {
        objCart.removeItemFromCart(this);
      });

      /**
       * Event: Change
       * Action: When input is changed Update number of products on cart view
       * Type: Event
       */
      this.inputQtyChange.change(function () {
        objCart.inputChangeOnCart(this);
      });

      /**
       * Event: Change
       * Action: When user select number of items on single view Show number of points
       * Type: Event
       */
      this.elementProductQuantity.change(function () {
        objCart.showLivePoints(this);
      });

      /**
       * Event: OnLoad
       * Action: Trigger Data Table
       * Type: Event
       */
      if (this.elementCartTable.length) {
        this.cartInit();
      }
    },

    /**
     * This function gets the token from browser cookie
     * @param {*} strCookieName
     */
    getTokenFromCookie: function (strCookieName) {
      var name = strCookieName + "=";
      var decodedCookie = decodeURIComponent(document.cookie);
      var splittedCookies = decodedCookie.split(";");
      for (var i = 0; i < splittedCookies.length; i++) {
        var singleCookie = splittedCookies[i];
        while (singleCookie.charAt(0) == " ") {
          singleCookie = singleCookie.substring(1);
        }
        if (singleCookie.indexOf(name) == 0) {
          return singleCookie.substring(name.length, singleCookie.length);
        }
      }
      return "";
    },

    /**
     * Asynchronous Request Get Data from API
     * It return all user information
     */
    getDataFromApi: function (strToken) {
      let responseData = "";
      // Set Url
      var strUrl = this.apiMeToGetData;
      // Init XMLHttpRequest
      var objRequest = new XMLHttpRequest();
      // Open Request
      objRequest.open("POST", strUrl);
      // Set Request Header Data Type
      objRequest.setRequestHeader("Accept", "application/json");
      // Set Request Type Header
      objRequest.setRequestHeader("Authorization", "Bearer " + strToken);
      // Set Content Type
      objRequest.setRequestHeader("Content-Type", "");
      // Request On Ready State Change
      objRequest.onreadystatechange = function () {
        if (objRequest.readyState === 4) {
          if (objRequest.status == 200) {
            responseData = objRequest.responseText;
            if (responseData.length > 0) {
              responseData = JSON.parse(responseData);
              if (responseData.data != null) {
                objCart.setLocalStorage(responseData.data);
                objCart.showUserHeader(responseData.data);
              } else {
                objCart.elementUserMetaInfo.hide();
              }
            }
          }
        }
      };
      objRequest.send();
    },

    /**
     * Set Local Storage on browser
     * @param {*} responseData
     */
    setLocalStorage: function (responseData) {
      localStorage.setItem("email", responseData.email);
      localStorage.setItem("points", responseData.pointBalance);
      localStorage.setItem("first-name", responseData.firstName);
      localStorage.setItem("last-name", responseData.lastName);
    },

    /**
     * Go to Single Product
     * @param {*} objThis
     */
    goToSingleView: function (objThis) {
      var permalink = $(objThis).data("permalink");
      if (permalink.length > 0) {
        window.location.href = permalink;
      }
    },

    /**
     *  Cart Init Data Table
     */
    cartInit: function () {
      this.elementCartTable.DataTable();
    },

    /**
     * Function to get Data and perform add to cart action
     * @param {*} objThis
     */
    getData: function (objThis) {
      var intProductId = parseInt($(objThis).data("id"));
      var intQuantity = parseInt($("#" + intProductId + "-quantity").val());
      if (intProductId > 0 && intQuantity > 0) {
        objCart.throwSpinner(objThis);
        objCart.addToCart(intProductId, intQuantity, objThis);
      } else {
        alert("Quantity must be greater than 0");
      }
    },

    /**
     * Function to change button text to spinner
     * @param {*} objThis
     */
    throwSpinner: function (objThis) {
      $(objThis).html(this.getIcon("spinner"));
    },

    /**
     * Function to submit sorting form on products page
     * @param {*} objThis
     */
    submitSortForm: function (objThis) {
      objThis.submit();
    },

    /**
     * Show User Header
     * @param {*} responseData
     */
    showUserHeader: function (responseData) {
      this.elementUserSpinner.hide();
      this.elementUserPoints.show();
      this.elementUserName.html(
        this.getIcon("user") +
          responseData.firstName +
          " " +
          responseData.lastName
      );
      this.elementUpdatePoints.html(responseData.pointBalance);
    },

    /**
     * Add Item to cart
     * @param {*} intProductId
     * @param {*} intQuantity
     * @param {*} objThis
     */
    addToCart: function (intProductId, intQuantity, objThis) {
      var objData = {
        action: "add-product-to-cart",
        post_id: intProductId,
        quantity: intQuantity,
      };
      $.getJSON(ajaxurl, objData, function (response) {
        if (response) {
          objCart.buttonAddToCart.html("Add To Cart");
          if (response.success_message.length > 0) {
            $(".hide-button").removeClass("hide-button");
            objCart.elementAfterAjaxMessage.html(response.success_message);
            objCart.elementAfterAjaxMessage.show();
            $("#single-product-points-" + response.product_id).text(
              response.product_points
            );
            objCart.updateCartMeta(
              response.total_products,
              response.total_points
            );
          }
          if (response.error_string.length > 0 && response.error == true) {
            objCart.elementAfterAjaxMessage.html(response.error_string);
            objCart.elementAfterAjaxMessage.show();
          }
        }
      });
    },

    /**
     * Change Points on user selection
     * @param {*} objThis
     */
    showLivePoints: function (objThis) {
      var intQty = $(objThis).val();
      if (intQty > 0) {
        var intPointPerProduct = $(objThis).data("product-point");
        this.elementShowPoints.text(intPointPerProduct * intQty + " Points");
      } else {
        this.elementShowPoints.text("");
      }
    },

    /**
     * Remove Item From Cart
     * @param {*} objThis
     */
    removeItemFromCart: function (objThis) {
      var intProductId = parseInt($(objThis).data("id"));
      if (intProductId > 0) {
        var objData = {
          action: "remove_item_from_cart",
          product_id: intProductId,
        };
        $.getJSON(ajaxurl, objData, function (response) {
          if (response.success == true) {
            objCart.elementAfterAjaxMessage.html(response.message);
            $("#remove-" + response.product_id).remove();
            objCart.updateCartMeta(
              response.total_products,
              response.total_points
            );
          }
        });
      }
    },

    /**
     * On Input Change
     * @param {*} objThis
     */
    inputChangeOnCart: function (objThis) {
      var intProductId = parseInt($(objThis).attr("id"));
      var intQuantity = parseInt($(objThis).val());
      this.addToCart(intProductId, intQuantity, objThis);
    },

    /**
     * Update Cart Meta On Cart Page
     * @param {*} intUpdatedProducts
     * @param {*} intUpdatedPoints
     */
    updateCartMeta: function (intUpdatedProducts, intUpdatedPoints) {
      if (this.elementCartMetaUpdateProducts.length) {
        this.elementCartMetaUpdateProducts.text(intUpdatedProducts);
      }
      if (this.elementCartMetaUpdatePoints.length) {
        this.elementCartMetaUpdatePoints.text(intUpdatedPoints);
      }
    },

    /**
     * This function CheckOut User
     */
    checkOutUser: function () {
      var userFirst = localStorage.getItem("first-name");
      var lastName = localStorage.getItem("last-name");
      var userPoints = localStorage.getItem("points");
      var userEmail = localStorage.getItem("email");
      var strToken = objCart.getTokenFromCookie(objCart.cookieName);

      objCart.checkOutShowAnimation("init");
      var objData = {
        action: "check_out_user",
        firstName: userFirst,
        lastName: lastName,
        userPoints: userPoints,
        userEmail: userEmail,
        strToken: strToken,
      };

      $.getJSON(ajaxurl, objData, function (objResponse) {
        if (objResponse.success == true) {
          objCart.updateCheckOutAction(
            objResponse,
            "success",
            objResponse.token
          );
        } else {
          objCart.elementAfterAjaxMessage.html(objResponse.message);
          objCart.updateCheckOutAction(
            objResponse,
            "failure",
            objResponse.token
          );
        }
      });
    },

    /**
     * Remove Local Storage
     */
    removeLocalStorage: function () {
      localStorage.removeItem("first-name");
      localStorage.removeItem("last-name");
      localStorage.removeItem("points");
      localStorage.removeItem("email");
    },

    /**
     * Update Check Out Action
     * @param {*} objResponse
     */
    updateCheckOutAction: function (objResponse, strType, strToken) {
      if (strType == "success") {
        objCart.updateUserPoints(
          objResponse.updated_points,
          objResponse.points,
          strToken
        );
        objCart.removeLocalStorage();
        objCart.checkOutShowAnimation("success");
        this.elementAfterAjaxMessage.html(objResponse.message);
      } else {
        objCart.checkOutShowAnimation("failure");
        this.elementAfterAjaxMessage.html(objResponse.message);
      }
    },

    /**
     * Animation on checkout button
     * @param {*} strType
     */
    checkOutShowAnimation: function (strType) {
      if (strType == "init") {
        this.elementCartBottomAnimation.show();
        this.elementCartAndMeta.hide();
      } else if (strType == "success") {
        this.elementCartBottomAnimation.hide();
        this.elementCartAndMeta.hide();
      } else if (strType == "failure") {
        this.elementCartBottomAnimation.hide();
        this.elementCartAndMeta.show();
      }
    },

    /**
     * Post Updated Points
     * @param {*} response
     */
    updateUserPoints: function (intUpdatedPoints, intPoints, strToken) {
      var objPostRequest = new XMLHttpRequest();
      objPostRequest.open("POST", objCart.apiUpdatePoints);
      objPostRequest.setRequestHeader("Authorization", "Bearer " + strToken);
      objPostRequest.setRequestHeader("Content-Type", "application/json");

      objPostRequest.onreadystatechange = function () {
        if (objPostRequest.readyState === 4) {
          if (objPostRequest.status == 200) {
            objCart.updateHeaderPoints(intUpdatedPoints);
          }
        }
      };
      objPostRequest.send(objCart.getResponseObject(intPoints));
    },

    /**
     * Get Updated JSON Object To Post To API
     * @param {*} intPoints
     */
    getResponseObject: function (intPoints) {
      var data =
        `{
              "Points": ` +
        -intPoints +
        `,
                "Type": "earned"
            }`;
      return data;
    },

    /**
     * Update Points in the User Header
     * @param {*} intPoints
     */
    updateHeaderPoints: function (intPoints) {
      if (this.elementUpdatePoints.length) {
        this.elementUpdatePoints.html(intPoints);
      }
    },

    /**
     * Handy function to return icons or HTML
     * @param {*} strIconType
     */
    getIcon: function (strIconType) {
      var htmlReturn = "";
      if (strIconType == "user") {
        htmlReturn = '<i class="fa fa-user-circle" aria-hidden="true"></i> ';
      }
      if (strIconType == "spinner") {
        htmlReturn = '<i class="fa-spin fa fa-cog" aria-hidden="true"></i> ';
      }
      return htmlReturn;
    },
  };

  //Cart Init Function
  objCart.initCart();
});
