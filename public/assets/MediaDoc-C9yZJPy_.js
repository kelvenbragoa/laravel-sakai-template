import{B as it,s as N,y as g,aa as G,H as P,R as D,a1 as $,o as l,c as u,a as d,a3 as T,p as s,h as f,Q as v,P as m,F as S,N as C,W as R,V as W,ad as nt,ae as at,aj as rt,L as gt,M as A,b as w,aI as Z,Z as K,aH as vt,K as bt,aG as It,i as x,T as yt,n as y,e as wt,t as M,r as j,g as St,aL as xt}from"./app-qDQxZF5W.js";import{U as E}from"./index-eXmtiCUl.js";import{s as X}from"./index-_drBej8F.js";import{s as Y}from"./index-JbwA7mDV.js";import{s as ot}from"./index-6jg3NOYW.js";import{s as lt}from"./index-BIqi3m-4.js";import{s as st}from"./index-B7aCCgGL.js";import{s as kt}from"./index-BvtLgeUk.js";import{P as Ct}from"./ProductService-BtITuo-x.js";import"./index-NOgjvH53.js";var Pt=function(t){var i=t.dt;return`
.p-galleria {
    overflow: hidden;
    border-style: solid;
    border-width: `.concat(i("galleria.border.width"),`;
    border-color: `).concat(i("galleria.border.color"),`;
    border-radius: `).concat(i("galleria.border.radius"),`;
}

.p-galleria-content {
    display: flex;
    flex-direction: column;
}

.p-galleria-items-container {
    display: flex;
    flex-direction: column;
    position: relative;
}

.p-galleria-items {
    position: relative;
    display: flex;
    height: 100%;
}

.p-galleria-nav-button {
    position: absolute !important;
    top: 50%;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    background: `).concat(i("galleria.nav.button.background"),`;
    color: `).concat(i("galleria.nav.button.color"),`;
    width: `).concat(i("galleria.nav.button.size"),`;
    height: `).concat(i("galleria.nav.button.size"),`;
    transition: background `).concat(i("galleria.transition.duration"),", color ").concat(i("galleria.transition.duration"),", outline-color ").concat(i("galleria.transition.duration"),", box-shadow ").concat(i("galleria.transition.duration"),`;
    margin: calc(-1 * calc(`).concat(i("galleria.nav.button.size"),") / 2) ").concat(i("galleria.nav.button.gutter")," 0 ").concat(i("galleria.nav.button.gutter"),`;
    padding: 0;
    user-select: none;
    border: 0 none;
    cursor: pointer;
    outline-color: transparent;
}

.p-galleria-nav-button:not(.p-disabled):hover {
    background: `).concat(i("galleria.nav.button.hover.background"),`;
    color: `).concat(i("galleria.nav.button.hover.color"),`;
}

.p-galleria-nav-button:not(.p-disabled):focus-visible {
    box-shadow: `).concat(i("galleria.nav.button.focus.ring.shadow"),`;
    outline: `).concat(i("galleria.nav.button.focus.ring.width")," ").concat(i("galleria.nav.button.focus.ring.style")," ").concat(i("galleria.nav.button.focus.ring.color"),`;
    outline-offset: `).concat(i("galleria.nav.button.focus.ring.offset"),`;
}

.p-galleria-next-icon,
.p-galleria-prev-icon {
    font-size: `).concat(i("galleria.nav.icon.size"),`;
    width: `).concat(i("galleria.nav.icon.size"),`;
    height: `).concat(i("galleria.nav.icon.size"),`;
}

.p-galleria-prev-button {
    border-radius: `).concat(i("galleria.nav.button.prev.border.radius"),`;
    left: 0;
}

.p-galleria-next-button {
    border-radius: `).concat(i("galleria.nav.button.next.border.radius"),`;
    right: 0;
}

.p-galleria-prev-button:dir(rtl) {
    left: auto;
    right: 0;
    transform: rotate(180deg);
}

.p-galleria-next-button:dir(rtl) {
    right: auto;
    left: 0;
    transform: rotate(180deg);
}

.p-galleria-item {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100%;
    width: 100%;
}

.p-galleria-hover-navigators .p-galleria-nav-button {
    pointer-events: none;
    opacity: 0;
    transition: opacity `).concat(i("galleria.transition.duration"),` ease-in-out;
}

.p-galleria-hover-navigators .p-galleria-items-container:hover .p-galleria-nav-button {
    pointer-events: all;
    opacity: 1;
}

.p-galleria-hover-navigators .p-galleria-items-container:hover .p-galleria-nav-button.p-disabled {
    pointer-events: none;
}

.p-galleria-caption {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    background: `).concat(i("galleria.caption.background"),`;
    color: `).concat(i("galleria.caption.color"),`;
    padding: `).concat(i("galleria.caption.padding"),`;
}

.p-galleria-thumbnails {
    display: flex;
    flex-direction: column;
    overflow: auto;
    flex-shrink: 0;
}

.p-galleria-thumbnail-nav-button {
    align-self: center;
    flex: 0 0 auto;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    position: relative;
    margin: 0 `).concat(i("galleria.thumbnail.nav.button.gutter"),`;
    padding: 0;
    border: none;
    user-select: none;
    cursor: pointer;
    background: transparent;
    color: `).concat(i("galleria.thumbnail.nav.button.color"),`;
    width: `).concat(i("galleria.thumbnail.nav.button.size"),`;
    height: `).concat(i("galleria.thumbnail.nav.button.size"),`;
    transition: background `).concat(i("galleria.transition.duration"),", color ").concat(i("galleria.transition.duration"),", outline-color ").concat(i("galleria.transition.duration"),`;
    outline-color: transparent;
    border-radius: `).concat(i("galleria.thumbnail.nav.button.border.radius"),`;
}

.p-galleria-thumbnail-nav-button:hover {
    background: `).concat(i("galleria.thumbnail.nav.button.hover.background"),`;
    color: `).concat(i("galleria.thumbnail.nav.button.hover.color"),`;
}

.p-galleria-thumbnail-nav-button:focus-visible {
    box-shadow: `).concat(i("galleria.thumbnail.nav.button.focus.ring.shadow"),`;
    outline: `).concat(i("galleria.thumbnail.nav.button.focus.ring.width")," ").concat(i("galleria.thumbnail.nav.button.focus.ring.style")," ").concat(i("galleria.thumbnail.nav.button.focus.ring.color"),`;
    outline-offset: `).concat(i("galleria.thumbnail.nav.button.focus.ring.offset"),`;
}

.p-galleria-thumbnail-nav-button .p-galleria-thumbnail-next-icon,
.p-galleria-thumbnail-nav-button .p-galleria-thumbnail-prev-icon {
    font-size: `).concat(i("galleria.thumbnail.nav.button.icon.size"),`;
    width: `).concat(i("galleria.thumbnail.nav.button.icon.size"),`;
    height: `).concat(i("galleria.thumbnail.nav.button.icon.size"),`;
}

.p-galleria-thumbnails-content {
    display: flex;
    flex-direction: row;
    background: `).concat(i("galleria.thumbnails.content.background"),`;
    padding: `).concat(i("galleria.thumbnails.content.padding"),`;
}

.p-galleria-thumbnails-viewport {
    overflow: hidden;
    width: 100%;
}

.p-galleria:not(.p-galleria-thumbnails-right):not(.p-galleria-thumbnails-left) .p-galleria-thumbnail-prev-button:dir(rtl),
.p-galleria:not(.p-galleria-thumbnails-right):not(.p-galleria-thumbnails-left) .p-galleria-thumbnail-next-button:dir(rtl) {
    transform: rotate(180deg);
}

.p-galleria-thumbnail-items {
    display: flex;
}

.p-galleria-thumbnail-item {
    overflow: auto;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    opacity: 0.5;
}

.p-galleria-thumbnail {
    outline-color: transparent;
}

.p-galleria-thumbnail-item:hover {
    opacity: 1;
    transition: opacity 0.3s;
}

.p-galleria-thumbnail-item-current {
    opacity: 1;
}

.p-galleria-thumbnails-left .p-galleria-content,
.p-galleria-thumbnails-right .p-galleria-content {
    flex-direction: row;
}

.p-galleria-thumbnails-left .p-galleria-items-container,
.p-galleria-thumbnails-right .p-galleria-items-container {
    flex-direction: row;
}

.p-galleria-thumbnails-left .p-galleria-items-container,
.p-galleria-thumbnails-top .p-galleria-items-container {
    order: 2;
}

.p-galleria-thumbnails-left .p-galleria-thumbnails,
.p-galleria-thumbnails-top .p-galleria-thumbnails {
    order: 1;
}

.p-galleria-thumbnails-left .p-galleria-thumbnails-content,
.p-galleria-thumbnails-right .p-galleria-thumbnails-content {
    flex-direction: column;
    flex-grow: 1;
}

.p-galleria-thumbnails-left .p-galleria-thumbnail-items,
.p-galleria-thumbnails-right .p-galleria-thumbnail-items {
    flex-direction: column;
    height: 100%;
}

.p-galleria-indicator-list {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: `).concat(i("galleria.indicator.list.padding"),`;
    gap: `).concat(i("galleria.indicator.list.gap"),`;
    margin: 0;
    list-style: none;
}

.p-galleria-indicator-button {
    display: inline-flex;
    align-items: center;
    background: `).concat(i("galleria.indicator.button.background"),`;
    width: `).concat(i("galleria.indicator.button.width"),`;
    height: `).concat(i("galleria.indicator.button.height"),`;
    transition: background `).concat(i("galleria.transition.duration"),", color ").concat(i("galleria.transition.duration"),", outline-color ").concat(i("galleria.transition.duration"),", box-shadow ").concat(i("galleria.transition.duration"),`;
    outline-color: transparent;
    border-radius: `).concat(i("galleria.indicator.button.border.radius"),`;
    margin: 0;
    padding: 0;
    border: none;
    user-select: none;
    cursor: pointer;
}

.p-galleria-indicator-button:hover {
    background: `).concat(i("galleria.indicator.button.hover.background"),`;
}

.p-galleria-indicator-button:focus-visible {
    box-shadow: `).concat(i("galleria.indicator.button.focus.ring.shadow"),`;
    outline: `).concat(i("galleria.indicator.button.focus.ring.width")," ").concat(i("galleria.indicator.button.focus.ring.style")," ").concat(i("galleria.indicator.button.focus.ring.color"),`;
    outline-offset: `).concat(i("galleria.indicator.button.focus.ring.offset"),`;
}

.p-galleria-indicator-active .p-galleria-indicator-button {
    background: `).concat(i("galleria.indicator.button.active.background"),`;
}

.p-galleria-indicators-left .p-galleria-items-container,
.p-galleria-indicators-right .p-galleria-items-container {
    flex-direction: row;
    align-items: center;
}

.p-galleria-indicators-left .p-galleria-items,
.p-galleria-indicators-top .p-galleria-items {
    order: 2;
}

.p-galleria-indicators-left .p-galleria-indicator-list,
.p-galleria-indicators-top .p-galleria-indicator-list {
    order: 1;
}

.p-galleria-indicators-left .p-galleria-indicator-list,
.p-galleria-indicators-right .p-galleria-indicator-list {
    flex-direction: column;
}

.p-galleria-inset-indicators .p-galleria-indicator-list {
    position: absolute;
    display: flex;
    z-index: 1;
    background: `).concat(i("galleria.inset.indicator.list.background"),`;
}

.p-galleria-inset-indicators .p-galleria-indicator-button {
    background: `).concat(i("galleria.inset.indicator.button.background"),`;
}

.p-galleria-inset-indicators .p-galleria-indicator-button:hover {
    background: `).concat(i("galleria.inset.indicator.button.hover.background"),`;
}

.p-galleria-inset-indicators .p-galleria-indicator-active .p-galleria-indicator-button {
    background: `).concat(i("galleria.inset.indicator.button.active.background"),`;
}

.p-galleria-inset-indicators.p-galleria-indicators-top .p-galleria-indicator-list {
    top: 0;
    left: 0;
    width: 100%;
    align-items: flex-start;
}

.p-galleria-inset-indicators.p-galleria-indicators-right .p-galleria-indicator-list {
    right: 0;
    top: 0;
    height: 100%;
    align-items: flex-end;
}

.p-galleria-inset-indicators.p-galleria-indicators-bottom .p-galleria-indicator-list {
    bottom: 0;
    left: 0;
    width: 100%;
    align-items: flex-end;
}

.p-galleria-inset-indicators.p-galleria-indicators-left .p-galleria-indicator-list {
    left: 0;
    top: 0;
    height: 100%;
    align-items: flex-start;
}

.p-galleria-mask {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.p-galleria-close-button {
    position: absolute !important;
    top: 0;
    right: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    margin: `).concat(i("galleria.close.button.gutter"),`;
    background: `).concat(i("galleria.close.button.background"),`;
    color: `).concat(i("galleria.close.button.color"),`;
    width: `).concat(i("galleria.close.button.size"),`;
    height: `).concat(i("galleria.close.button.size"),`;
    padding: 0;
    border: none;
    user-select: none;
    cursor: pointer;
    border-radius: `).concat(i("galleria.close.button.border.radius"),`;
    outline-color: transparent;
    transition: background `).concat(i("galleria.transition.duration"),", color ").concat(i("galleria.transition.duration"),", outline-color ").concat(i("galleria.transition.duration"),`;
}

.p-galleria-close-icon {
    font-size: `).concat(i("galleria.close.button.icon.size"),`;
    width: `).concat(i("galleria.close.button.icon.size"),`;
    height: `).concat(i("galleria.close.button.icon.size"),`;
}

.p-galleria-close-button:hover {
    background: `).concat(i("galleria.close.button.hover.background"),`;
    color: `).concat(i("galleria.close.button.hover.color"),`;
}

.p-galleria-close-button:focus-visible {
    box-shadow: `).concat(i("galleria.close.button.focus.ring.shadow"),`;
    outline: `).concat(i("galleria.close.button.focus.ring.width")," ").concat(i("galleria.close.button.focus.ring.style")," ").concat(i("galleria.close.button.focus.ring.color"),`;
    outline-offset: `).concat(i("galleria.close.button.focus.ring.offset"),`;
}

.p-galleria-mask .p-galleria-nav-button {
    position: fixed;
    top: 50%;
}

.p-galleria-enter-active {
    transition: all 150ms cubic-bezier(0, 0, 0.2, 1);
}

.p-galleria-leave-active {
    transition: all 150ms cubic-bezier(0.4, 0, 0.2, 1);
}

.p-galleria-enter-from,
.p-galleria-leave-to {
    opacity: 0;
    transform: scale(0.7);
}

.p-galleria-enter-active .p-galleria-nav-button {
    opacity: 0;
}

.p-items-hidden .p-galleria-thumbnail-item {
    visibility: hidden;
}

.p-items-hidden .p-galleria-thumbnail-item.p-galleria-thumbnail-item-active {
    visibility: visible;
}
`)},Tt={mask:"p-galleria-mask p-overlay-mask p-overlay-mask-enter",root:function(t){var i=t.instance,n=i.$attrs.showThumbnails&&i.getPositionClass("p-galleria-thumbnails",i.$attrs.thumbnailsPosition),r=i.$attrs.showIndicators&&i.getPositionClass("p-galleria-indicators",i.$attrs.indicatorsPosition);return["p-galleria p-component",{"p-galleria-fullscreen":i.$attrs.fullScreen,"p-galleria-inset-indicators":i.$attrs.showIndicatorsOnItem,"p-galleria-hover-navigators":i.$attrs.showItemNavigatorsOnHover&&!i.$attrs.fullScreen},n,r]},closeButton:"p-galleria-close-button",closeIcon:"p-galleria-close-icon",header:"p-galleria-header",content:"p-galleria-content",footer:"p-galleria-footer",itemsContainer:"p-galleria-items-container",items:"p-galleria-items",prevButton:function(t){var i=t.instance;return["p-galleria-prev-button p-galleria-nav-button",{"p-disabled":i.isNavBackwardDisabled()}]},prevIcon:"p-galleria-prev-icon",item:"p-galleria-item",nextButton:function(t){var i=t.instance;return["p-galleria-next-button p-galleria-nav-button",{"p-disabled":i.isNavForwardDisabled()}]},nextIcon:"p-galleria-next-icon",caption:"p-galleria-caption",indicatorList:"p-galleria-indicator-list",indicator:function(t){var i=t.instance,n=t.index;return["p-galleria-indicator",{"p-galleria-indicator-active":i.isIndicatorItemActive(n)}]},indicatorButton:"p-galleria-indicator-button",thumbnails:"p-galleria-thumbnails",thumbnailContent:"p-galleria-thumbnails-content",thumbnailPrevButton:function(t){var i=t.instance;return["p-galleria-thumbnail-prev-button p-galleria-thumbnail-nav-button",{"p-disabled":i.isNavBackwardDisabled()}]},thumbnailPrevIcon:"p-galleria-thumbnail-prev-icon",thumbnailsViewport:"p-galleria-thumbnails-viewport",thumbnailItems:"p-galleria-thumbnail-items",thumbnailItem:function(t){var i=t.instance,n=t.index,r=t.activeIndex;return["p-galleria-thumbnail-item",{"p-galleria-thumbnail-item-current":r===n,"p-galleria-thumbnail-item-active":i.isItemActive(n),"p-galleria-thumbnail-item-start":i.firstItemAciveIndex()===n,"p-galleria-thumbnail-item-end":i.lastItemActiveIndex()===n}]},thumbnail:"p-galleria-thumbnail",thumbnailNextButton:function(t){var i=t.instance;return["p-galleria-thumbnail-next-button p-galleria-thumbnail-nav-button",{"p-disabled":i.isNavForwardDisabled()}]},thumbnailNextIcon:"p-galleria-thumbnail-next-icon"},Vt=it.extend({name:"galleria",theme:Pt,classes:Tt}),At={name:"BaseGalleria",extends:N,props:{id:{type:String,default:null},value:{type:Array,default:null},activeIndex:{type:Number,default:0},fullScreen:{type:Boolean,default:!1},visible:{type:Boolean,default:!1},numVisible:{type:Number,default:3},responsiveOptions:{type:Array,default:null},showItemNavigators:{type:Boolean,default:!1},showThumbnailNavigators:{type:Boolean,default:!0},showItemNavigatorsOnHover:{type:Boolean,default:!1},changeItemOnIndicatorHover:{type:Boolean,default:!1},circular:{type:Boolean,default:!1},autoPlay:{type:Boolean,default:!1},transitionInterval:{type:Number,default:4e3},showThumbnails:{type:Boolean,default:!0},thumbnailsPosition:{type:String,default:"bottom"},verticalThumbnailViewPortHeight:{type:String,default:"300px"},showIndicators:{type:Boolean,default:!1},showIndicatorsOnItem:{type:Boolean,default:!1},indicatorsPosition:{type:String,default:"bottom"},baseZIndex:{type:Number,default:0},maskClass:{type:String,default:null},containerStyle:{type:null,default:null},containerClass:{type:null,default:null},containerProps:{type:null,default:null},prevButtonProps:{type:null,default:null},nextButtonProps:{type:null,default:null},ariaLabel:{type:String,default:null},ariaRoledescription:{type:String,default:null}},style:Vt,provide:function(){return{$pcGalleria:this,$parentInstance:this}}};function V(e){return Nt(e)||Lt(e)||Ot(e)||Bt()}function Bt(){throw new TypeError(`Invalid attempt to spread non-iterable instance.
In order to be iterable, non-array objects must have a [Symbol.iterator]() method.`)}function Ot(e,t){if(e){if(typeof e=="string")return z(e,t);var i={}.toString.call(e).slice(8,-1);return i==="Object"&&e.constructor&&(i=e.constructor.name),i==="Map"||i==="Set"?Array.from(e):i==="Arguments"||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(i)?z(e,t):void 0}}function Lt(e){if(typeof Symbol<"u"&&e[Symbol.iterator]!=null||e["@@iterator"]!=null)return Array.from(e)}function Nt(e){if(Array.isArray(e))return z(e)}function z(e,t){(t==null||t>e.length)&&(t=e.length);for(var i=0,n=Array(t);i<t;i++)n[i]=e[i];return n}var ct={name:"GalleriaItem",hostName:"Galleria",extends:N,emits:["start-slideshow","stop-slideshow","update:activeIndex"],props:{circular:{type:Boolean,default:!1},activeIndex:{type:Number,default:0},value:{type:Array,default:null},showItemNavigators:{type:Boolean,default:!0},showIndicators:{type:Boolean,default:!0},slideShowActive:{type:Boolean,default:!0},changeItemOnIndicatorHover:{type:Boolean,default:!0},autoPlay:{type:Boolean,default:!1},templates:{type:null,default:null},id:{type:String,default:null}},mounted:function(){this.autoPlay&&this.$emit("start-slideshow")},methods:{getIndicatorPTOptions:function(t){return{context:{highlighted:this.activeIndex===t}}},next:function(){var t=this.activeIndex+1,i=this.circular&&this.value.length-1===this.activeIndex?0:t;this.$emit("update:activeIndex",i)},prev:function(){var t=this.activeIndex!==0?this.activeIndex-1:0,i=this.circular&&this.activeIndex===0?this.value.length-1:t;this.$emit("update:activeIndex",i)},stopSlideShow:function(){this.slideShowActive&&this.stopSlideShow&&this.$emit("stop-slideshow")},navBackward:function(t){this.stopSlideShow(),this.prev(),t&&t.cancelable&&t.preventDefault()},navForward:function(t){this.stopSlideShow(),this.next(),t&&t.cancelable&&t.preventDefault()},onIndicatorClick:function(t){this.stopSlideShow(),this.$emit("update:activeIndex",t)},onIndicatorMouseEnter:function(t){this.changeItemOnIndicatorHover&&(this.stopSlideShow(),this.$emit("update:activeIndex",t))},onIndicatorKeyDown:function(t,i){switch(t.code){case"Enter":case"NumpadEnter":case"Space":this.stopSlideShow(),this.$emit("update:activeIndex",i),t.preventDefault();break;case"ArrowRight":this.onRightKey();break;case"ArrowLeft":this.onLeftKey();break;case"Home":this.onHomeKey(),t.preventDefault();break;case"End":this.onEndKey(),t.preventDefault();break;case"Tab":this.onTabKey();break;case"ArrowDown":case"ArrowUp":case"PageUp":case"PageDown":t.preventDefault();break}},onRightKey:function(){var t=V(g(this.$refs.indicatorContent,'[data-pc-section="indicator"]')),i=this.findFocusedIndicatorIndex();this.changedFocusedIndicator(i,i+1===t.length?t.length-1:i+1)},onLeftKey:function(){var t=this.findFocusedIndicatorIndex();this.changedFocusedIndicator(t,t-1<=0?0:t-1)},onHomeKey:function(){var t=this.findFocusedIndicatorIndex();this.changedFocusedIndicator(t,0)},onEndKey:function(){var t=V(g(this.$refs.indicatorContent,'[data-pc-section="indicator"]')),i=this.findFocusedIndicatorIndex();this.changedFocusedIndicator(i,t.length-1)},onTabKey:function(){var t=V(g(this.$refs.indicatorContent,'[data-pc-section="indicator"]')),i=t.findIndex(function(a){return G(a,"data-p-active")===!0}),n=P(this.$refs.indicatorContent,'[data-pc-section="indicator"] > [tabindex="0"]'),r=t.findIndex(function(a){return a===n.parentElement});t[r].children[0].tabIndex="-1",t[i].children[0].tabIndex="0"},findFocusedIndicatorIndex:function(){var t=V(g(this.$refs.indicatorContent,'[data-pc-section="indicator"]')),i=P(this.$refs.indicatorContent,'[data-pc-section="indicator"] > [tabindex="0"]');return t.findIndex(function(n){return n===i.parentElement})},changedFocusedIndicator:function(t,i){var n=V(g(this.$refs.indicatorContent,'[data-pc-section="indicator"]'));n[t].children[0].tabIndex="-1",n[i].children[0].tabIndex="0",n[i].children[0].focus()},isIndicatorItemActive:function(t){return this.activeIndex===t},isNavBackwardDisabled:function(){return!this.circular&&this.activeIndex===0},isNavForwardDisabled:function(){return!this.circular&&this.activeIndex===this.value.length-1},ariaSlideNumber:function(t){return this.$primevue.config.locale.aria?this.$primevue.config.locale.aria.slideNumber.replace(/{slideNumber}/g,t):void 0},ariaPageLabel:function(t){return this.$primevue.config.locale.aria?this.$primevue.config.locale.aria.pageLabel.replace(/{page}/g,t):void 0}},computed:{activeItem:function(){return this.value[this.activeIndex]},ariaSlideLabel:function(){return this.$primevue.config.locale.aria?this.$primevue.config.locale.aria.slide:void 0}},components:{ChevronLeftIcon:X,ChevronRightIcon:Y},directives:{ripple:D}},jt=["disabled"],_t=["id","aria-label","aria-roledescription"],Dt=["disabled"],$t=["aria-label","aria-selected","aria-controls","onClick","onMouseenter","onKeydown","data-p-active"],Kt=["tabindex"];function Et(e,t,i,n,r,a){var h=$("ripple");return l(),u("div",s({class:e.cx("itemsContainer")},e.ptm("itemsContainer")),[d("div",s({class:e.cx("items")},e.ptm("items")),[i.showItemNavigators?T((l(),u("button",s({key:0,type:"button",class:e.cx("prevButton"),onClick:t[0]||(t[0]=function(c){return a.navBackward(c)}),disabled:a.isNavBackwardDisabled()},e.ptm("prevButton"),{"data-pc-group-section":"itemnavigator"}),[(l(),f(v(i.templates.previousitemicon||"ChevronLeftIcon"),s({class:e.cx("prevIcon")},e.ptm("prevIcon")),null,16,["class"]))],16,jt)),[[h]]):m("",!0),d("div",s({id:i.id+"_item_"+i.activeIndex,class:e.cx("item"),role:"group","aria-label":a.ariaSlideNumber(i.activeIndex+1),"aria-roledescription":a.ariaSlideLabel},e.ptm("item")),[i.templates.item?(l(),f(v(i.templates.item),{key:0,item:a.activeItem},null,8,["item"])):m("",!0)],16,_t),i.showItemNavigators?T((l(),u("button",s({key:1,type:"button",class:e.cx("nextButton"),onClick:t[1]||(t[1]=function(c){return a.navForward(c)}),disabled:a.isNavForwardDisabled()},e.ptm("nextButton"),{"data-pc-group-section":"itemnavigator"}),[(l(),f(v(i.templates.nextitemicon||"ChevronRightIcon"),s({class:e.cx("nextIcon")},e.ptm("nextIcon")),null,16,["class"]))],16,Dt)),[[h]]):m("",!0),i.templates.caption?(l(),u("div",s({key:2,class:e.cx("caption")},e.ptm("caption")),[i.templates.caption?(l(),f(v(i.templates.caption),{key:0,item:a.activeItem},null,8,["item"])):m("",!0)],16)):m("",!0)],16),i.showIndicators?(l(),u("ul",s({key:0,ref:"indicatorContent",class:e.cx("indicatorList")},e.ptm("indicatorList")),[(l(!0),u(S,null,C(i.value,function(c,o){return l(),u("li",s({key:"p-galleria-indicator-".concat(o),class:e.cx("indicator",{index:o}),"aria-label":a.ariaPageLabel(o+1),"aria-selected":i.activeIndex===o,"aria-controls":i.id+"_item_"+o,onClick:function(b){return a.onIndicatorClick(o)},onMouseenter:function(b){return a.onIndicatorMouseEnter(o)},onKeydown:function(b){return a.onIndicatorKeyDown(b,o)},ref_for:!0},e.ptm("indicator",a.getIndicatorPTOptions(o)),{"data-p-active":a.isIndicatorItemActive(o)}),[i.templates.indicator?m("",!0):(l(),u("button",s({key:0,type:"button",tabindex:i.activeIndex===o?"0":"-1",class:e.cx("indicatorButton"),ref_for:!0},e.ptm("indicatorButton",a.getIndicatorPTOptions(o))),null,16,Kt)),i.templates.indicator?(l(),f(v(i.templates.indicator),{key:1,index:o,activeIndex:i.activeIndex,tabindex:i.activeIndex===o?"0":"-1"},null,8,["index","activeIndex","tabindex"])):m("",!0)],16,$t)}),128))],16)):m("",!0)],16)}ct.render=Et;function F(e){return zt(e)||Mt(e)||Rt(e)||Ft()}function Ft(){throw new TypeError(`Invalid attempt to spread non-iterable instance.
In order to be iterable, non-array objects must have a [Symbol.iterator]() method.`)}function Rt(e,t){if(e){if(typeof e=="string")return H(e,t);var i={}.toString.call(e).slice(8,-1);return i==="Object"&&e.constructor&&(i=e.constructor.name),i==="Map"||i==="Set"?Array.from(e):i==="Arguments"||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(i)?H(e,t):void 0}}function Mt(e){if(typeof Symbol<"u"&&e[Symbol.iterator]!=null||e["@@iterator"]!=null)return Array.from(e)}function zt(e){if(Array.isArray(e))return H(e)}function H(e,t){(t==null||t>e.length)&&(t=e.length);for(var i=0,n=Array(t);i<t;i++)n[i]=e[i];return n}var ut={name:"GalleriaThumbnails",hostName:"Galleria",extends:N,emits:["stop-slideshow","update:activeIndex"],props:{containerId:{type:String,default:null},value:{type:Array,default:null},numVisible:{type:Number,default:3},activeIndex:{type:Number,default:0},isVertical:{type:Boolean,default:!1},slideShowActive:{type:Boolean,default:!1},circular:{type:Boolean,default:!1},responsiveOptions:{type:Array,default:null},contentHeight:{type:String,default:"300px"},showThumbnailNavigators:{type:Boolean,default:!0},templates:{type:null,default:null},prevButtonProps:{type:null,default:null},nextButtonProps:{type:null,default:null}},startPos:null,thumbnailsStyle:null,sortedResponsiveOptions:null,data:function(){return{d_numVisible:this.numVisible,d_oldNumVisible:this.numVisible,d_activeIndex:this.activeIndex,d_oldActiveItemIndex:this.activeIndex,totalShiftedItems:0,page:0}},watch:{numVisible:function(t,i){this.d_numVisible=t,this.d_oldNumVisible=i},activeIndex:function(t,i){this.d_activeIndex=t,this.d_oldActiveItemIndex=i}},mounted:function(){this.createStyle(),this.calculatePosition(),this.responsiveOptions&&this.bindDocumentListeners()},updated:function(){var t=this.totalShiftedItems;(this.d_oldNumVisible!==this.d_numVisible||this.d_oldActiveItemIndex!==this.d_activeIndex)&&(this.d_activeIndex<=this.getMedianItemIndex()?t=0:this.value.length-this.d_numVisible+this.getMedianItemIndex()<this.d_activeIndex?t=this.d_numVisible-this.value.length:this.value.length-this.d_numVisible<this.d_activeIndex&&this.d_numVisible%2===0?t=this.d_activeIndex*-1+this.getMedianItemIndex()+1:t=this.d_activeIndex*-1+this.getMedianItemIndex(),t!==this.totalShiftedItems&&(this.totalShiftedItems=t),this.$refs.itemsContainer.style.transform=this.isVertical?"translate3d(0, ".concat(t*(100/this.d_numVisible),"%, 0)"):"translate3d(".concat(t*(100/this.d_numVisible),"%, 0, 0)"),this.d_oldActiveItemIndex!==this.d_activeIndex&&(document.body.setAttribute("data-p-items-hidden","false"),!this.isUnstyled&&R(this.$refs.itemsContainer,"p-items-hidden"),this.$refs.itemsContainer.style.transition="transform 500ms ease 0s"),this.d_oldActiveItemIndex=this.d_activeIndex,this.d_oldNumVisible=this.d_numVisible)},beforeUnmount:function(){this.responsiveOptions&&this.unbindDocumentListeners(),this.thumbnailsStyle&&this.thumbnailsStyle.parentNode.removeChild(this.thumbnailsStyle)},methods:{step:function(t){var i=this.totalShiftedItems+t;t<0&&-1*i+this.d_numVisible>this.value.length-1?i=this.d_numVisible-this.value.length:t>0&&i>0&&(i=0),this.circular&&(t<0&&this.value.length-1===this.d_activeIndex?i=0:t>0&&this.d_activeIndex===0&&(i=this.d_numVisible-this.value.length)),this.$refs.itemsContainer&&(document.body.setAttribute("data-p-items-hidden","false"),!this.isUnstyled&&R(this.$refs.itemsContainer,"p-items-hidden"),this.$refs.itemsContainer.style.transform=this.isVertical?"translate3d(0, ".concat(i*(100/this.d_numVisible),"%, 0)"):"translate3d(".concat(i*(100/this.d_numVisible),"%, 0, 0)"),this.$refs.itemsContainer.style.transition="transform 500ms ease 0s"),this.totalShiftedItems=i},stopSlideShow:function(){this.slideShowActive&&this.stopSlideShow&&this.$emit("stop-slideshow")},getMedianItemIndex:function(){var t=Math.floor(this.d_numVisible/2);return this.d_numVisible%2?t:t-1},navBackward:function(t){this.stopSlideShow();var i=this.d_activeIndex!==0?this.d_activeIndex-1:0,n=i+this.totalShiftedItems;this.d_numVisible-n-1>this.getMedianItemIndex()&&(-1*this.totalShiftedItems!==0||this.circular)&&this.step(1);var r=this.circular&&this.d_activeIndex===0?this.value.length-1:i;this.$emit("update:activeIndex",r),t.cancelable&&t.preventDefault()},navForward:function(t){this.stopSlideShow();var i=this.d_activeIndex===this.value.length-1?this.value.length-1:this.d_activeIndex+1;i+this.totalShiftedItems>this.getMedianItemIndex()&&(-1*this.totalShiftedItems<this.getTotalPageNumber()-1||this.circular)&&this.step(-1);var n=this.circular&&this.value.length-1===this.d_activeIndex?0:i;this.$emit("update:activeIndex",n),t.cancelable&&t.preventDefault()},onItemClick:function(t){this.stopSlideShow();var i=t;if(i!==this.d_activeIndex){var n=i+this.totalShiftedItems,r=0;i<this.d_activeIndex?(r=this.d_numVisible-n-1-this.getMedianItemIndex(),r>0&&-1*this.totalShiftedItems!==0&&this.step(r)):(r=this.getMedianItemIndex()-n,r<0&&-1*this.totalShiftedItems<this.getTotalPageNumber()-1&&this.step(r)),this.$emit("update:activeIndex",i)}},onThumbnailKeydown:function(t,i){switch((t.code==="Enter"||t.code==="NumpadEnter"||t.code==="Space")&&(this.onItemClick(i),t.preventDefault()),t.code){case"ArrowRight":this.onRightKey();break;case"ArrowLeft":this.onLeftKey();break;case"Home":this.onHomeKey(),t.preventDefault();break;case"End":this.onEndKey(),t.preventDefault();break;case"ArrowUp":case"ArrowDown":t.preventDefault();break;case"Tab":this.onTabKey();break}},onRightKey:function(){var t=g(this.$refs.itemsContainer,'[data-pc-section="thumbnailitem"]'),i=this.findFocusedIndicatorIndex();this.changedFocusedIndicator(i,i+1===t.length?t.length-1:i+1)},onLeftKey:function(){var t=this.findFocusedIndicatorIndex();this.changedFocusedIndicator(t,t-1<=0?0:t-1)},onHomeKey:function(){var t=this.findFocusedIndicatorIndex();this.changedFocusedIndicator(t,0)},onEndKey:function(){var t=g(this.$refs.itemsContainer,'[data-pc-section="thumbnailitem"]'),i=this.findFocusedIndicatorIndex();this.changedFocusedIndicator(i,t.length-1)},onTabKey:function(){var t=F(g(this.$refs.itemsContainer,'[data-pc-section="thumbnailitem"]')),i=t.findIndex(function(a){return G(a,"data-p-active")===!0}),n=P(this.$refs.itemsContainer,'[tabindex="0"]'),r=t.findIndex(function(a){return a===n.parentElement});t[r].children[0].tabIndex="-1",t[i].children[0].tabIndex="0"},findFocusedIndicatorIndex:function(){var t=F(g(this.$refs.itemsContainer,'[data-pc-section="thumbnailitem"]')),i=P(this.$refs.itemsContainer,'[data-pc-section="thumbnailitem"] > [tabindex="0"]');return t.findIndex(function(n){return n===i.parentElement})},changedFocusedIndicator:function(t,i){var n=g(this.$refs.itemsContainer,'[data-pc-section="thumbnailitem"]');n[t].children[0].tabIndex="-1",n[i].children[0].tabIndex="0",n[i].children[0].focus()},onTransitionEnd:function(t){this.$refs.itemsContainer&&t.propertyName==="transform"&&(document.body.setAttribute("data-p-items-hidden","true"),!this.isUnstyled&&W(this.$refs.itemsContainer,"p-items-hidden"),this.$refs.itemsContainer.style.transition="")},onTouchStart:function(t){var i=t.changedTouches[0];this.startPos={x:i.pageX,y:i.pageY}},onTouchMove:function(t){t.cancelable&&t.preventDefault()},onTouchEnd:function(t){var i=t.changedTouches[0];this.isVertical?this.changePageOnTouch(t,i.pageY-this.startPos.y):this.changePageOnTouch(t,i.pageX-this.startPos.x)},changePageOnTouch:function(t,i){i<0?this.navForward(t):this.navBackward(t)},getTotalPageNumber:function(){return this.value.length>this.d_numVisible?this.value.length-this.d_numVisible+1:0},createStyle:function(){if(!this.thumbnailsStyle){var t;this.thumbnailsStyle=document.createElement("style"),this.thumbnailsStyle.type="text/css",nt(this.thumbnailsStyle,"nonce",(t=this.$primevue)===null||t===void 0||(t=t.config)===null||t===void 0||(t=t.csp)===null||t===void 0?void 0:t.nonce),document.body.appendChild(this.thumbnailsStyle)}var i=`
                #`.concat(this.containerId,` [data-pc-section="thumbnailitem"] {
                    flex: 1 0 `).concat(100/this.d_numVisible,`%
                }
            `);if(this.responsiveOptions&&!this.isUnstyled){this.sortedResponsiveOptions=F(this.responsiveOptions);var n=at();this.sortedResponsiveOptions.sort(function(h,c){var o=h.breakpoint,p=c.breakpoint;return rt(o,p,-1,n)});for(var r=0;r<this.sortedResponsiveOptions.length;r++){var a=this.sortedResponsiveOptions[r];i+=`
                        @media screen and (max-width: `.concat(a.breakpoint,`) {
                            #`).concat(this.containerId,` .p-galleria-thumbnail-item {
                                flex: 1 0 `).concat(100/a.numVisible,`%
                            }
                        }
                    `)}}this.thumbnailsStyle.innerHTML=i},calculatePosition:function(){if(this.$refs.itemsContainer&&this.sortedResponsiveOptions){for(var t=window.innerWidth,i={numVisible:this.numVisible},n=0;n<this.sortedResponsiveOptions.length;n++){var r=this.sortedResponsiveOptions[n];parseInt(r.breakpoint,10)>=t&&(i=r)}this.d_numVisible!==i.numVisible&&(this.d_numVisible=i.numVisible)}},bindDocumentListeners:function(){var t=this;this.documentResizeListener||(this.documentResizeListener=function(){t.calculatePosition()},window.addEventListener("resize",this.documentResizeListener))},unbindDocumentListeners:function(){this.documentResizeListener&&(window.removeEventListener("resize",this.documentResizeListener),this.documentResizeListener=null)},isNavBackwardDisabled:function(){return!this.circular&&this.d_activeIndex===0||this.value.length<=this.d_numVisible},isNavForwardDisabled:function(){return!this.circular&&this.d_activeIndex===this.value.length-1||this.value.length<=this.d_numVisible},firstItemAciveIndex:function(){return this.totalShiftedItems*-1},lastItemActiveIndex:function(){return this.firstItemAciveIndex()+this.d_numVisible-1},isItemActive:function(t){return this.firstItemAciveIndex()<=t&&this.lastItemActiveIndex()>=t},ariaPageLabel:function(t){return this.$primevue.config.locale.aria?this.$primevue.config.locale.aria.pageLabel.replace(/{page}/g,t):void 0}},computed:{ariaPrevButtonLabel:function(){return this.$primevue.config.locale.aria?this.$primevue.config.locale.aria.prevPageLabel:void 0},ariaNextButtonLabel:function(){return this.$primevue.config.locale.aria?this.$primevue.config.locale.aria.nextPageLabel:void 0}},components:{ChevronLeftIcon:X,ChevronRightIcon:Y,ChevronUpIcon:lt,ChevronDownIcon:ot},directives:{ripple:D}};function B(e){"@babel/helpers - typeof";return B=typeof Symbol=="function"&&typeof Symbol.iterator=="symbol"?function(t){return typeof t}:function(t){return t&&typeof Symbol=="function"&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},B(e)}function q(e,t){var i=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter(function(r){return Object.getOwnPropertyDescriptor(e,r).enumerable})),i.push.apply(i,n)}return i}function _(e){for(var t=1;t<arguments.length;t++){var i=arguments[t]!=null?arguments[t]:{};t%2?q(Object(i),!0).forEach(function(n){Ht(e,n,i[n])}):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(i)):q(Object(i)).forEach(function(n){Object.defineProperty(e,n,Object.getOwnPropertyDescriptor(i,n))})}return e}function Ht(e,t,i){return(t=Ut(t))in e?Object.defineProperty(e,t,{value:i,enumerable:!0,configurable:!0,writable:!0}):e[t]=i,e}function Ut(e){var t=Gt(e,"string");return B(t)=="symbol"?t:t+""}function Gt(e,t){if(B(e)!="object"||!e)return e;var i=e[Symbol.toPrimitive];if(i!==void 0){var n=i.call(e,t||"default");if(B(n)!="object")return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return(t==="string"?String:Number)(e)}var Wt=["disabled","aria-label"],Xt=["data-p-active","aria-selected","aria-controls","onKeydown","data-p-galleria-thumbnail-item-current","data-p-galleria-thumbnail-item-active","data-p-galleria-thumbnail-item-start","data-p-galleria-thumbnail-item-end"],Yt=["tabindex","aria-label","aria-current","onClick"],Zt=["disabled","aria-label"];function qt(e,t,i,n,r,a){var h=$("ripple");return l(),u("div",s({class:e.cx("thumbnails")},e.ptm("thumbnails")),[d("div",s({class:e.cx("thumbnailContent")},e.ptm("thumbnailContent")),[i.showThumbnailNavigators?T((l(),u("button",s({key:0,class:e.cx("thumbnailPrevButton"),disabled:a.isNavBackwardDisabled(),type:"button","aria-label":a.ariaPrevButtonLabel,onClick:t[0]||(t[0]=function(c){return a.navBackward(c)})},_(_({},i.prevButtonProps),e.ptm("thumbnailPrevButton")),{"data-pc-group-section":"thumbnailnavigator"}),[(l(),f(v(i.templates.previousthumbnailicon||(i.isVertical?"ChevronUpIcon":"ChevronLeftIcon")),s({class:e.cx("thumbnailPrevIcon")},e.ptm("thumbnailPrevIcon")),null,16,["class"]))],16,Wt)),[[h]]):m("",!0),d("div",s({class:e.cx("thumbnailsViewport"),style:{height:i.isVertical?i.contentHeight:""}},e.ptm("thumbnailsViewport")),[d("div",s({ref:"itemsContainer",class:e.cx("thumbnailItems"),role:"tablist",onTransitionend:t[1]||(t[1]=function(c){return a.onTransitionEnd(c)}),onTouchstart:t[2]||(t[2]=function(c){return a.onTouchStart(c)}),onTouchmove:t[3]||(t[3]=function(c){return a.onTouchMove(c)}),onTouchend:t[4]||(t[4]=function(c){return a.onTouchEnd(c)})},e.ptm("thumbnailItems")),[(l(!0),u(S,null,C(i.value,function(c,o){return l(),u("div",s({key:"p-galleria-thumbnail-item-".concat(o),class:e.cx("thumbnailItem",{index:o,activeIndex:i.activeIndex}),role:"tab","data-p-active":i.activeIndex===o,"aria-selected":i.activeIndex===o,"aria-controls":i.containerId+"_item_"+o,onKeydown:function(b){return a.onThumbnailKeydown(b,o)},ref_for:!0},e.ptm("thumbnailItem"),{"data-p-galleria-thumbnail-item-current":i.activeIndex===o,"data-p-galleria-thumbnail-item-active":a.isItemActive(o),"data-p-galleria-thumbnail-item-start":a.firstItemAciveIndex()===o,"data-p-galleria-thumbnail-item-end":a.lastItemActiveIndex()===o}),[d("div",s({class:e.cx("thumbnail"),tabindex:i.activeIndex===o?"0":"-1","aria-label":a.ariaPageLabel(o+1),"aria-current":i.activeIndex===o?"page":void 0,onClick:function(b){return a.onItemClick(o)},ref_for:!0},e.ptm("thumbnail")),[i.templates.thumbnail?(l(),f(v(i.templates.thumbnail),{key:0,item:c},null,8,["item"])):m("",!0)],16,Yt)],16,Xt)}),128))],16)],16),i.showThumbnailNavigators?T((l(),u("button",s({key:1,class:e.cx("thumbnailNextButton"),disabled:a.isNavForwardDisabled(),type:"button","aria-label":a.ariaNextButtonLabel,onClick:t[5]||(t[5]=function(c){return a.navForward(c)})},_(_({},i.nextButtonProps),e.ptm("thumbnailNextButton")),{"data-pc-group-section":"thumbnailnavigator"}),[(l(),f(v(i.templates.nextthumbnailicon||(i.isVertical?"ChevronDownIcon":"ChevronRightIcon")),s({class:e.cx("thumbnailNextIcon")},e.ptm("thumbnailNextIcon")),null,16,["class"]))],16,Zt)),[[h]]):m("",!0)],16)],16)}ut.render=qt;function O(e){"@babel/helpers - typeof";return O=typeof Symbol=="function"&&typeof Symbol.iterator=="symbol"?function(t){return typeof t}:function(t){return t&&typeof Symbol=="function"&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},O(e)}function Q(e,t){var i=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter(function(r){return Object.getOwnPropertyDescriptor(e,r).enumerable})),i.push.apply(i,n)}return i}function J(e){for(var t=1;t<arguments.length;t++){var i=arguments[t]!=null?arguments[t]:{};t%2?Q(Object(i),!0).forEach(function(n){Qt(e,n,i[n])}):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(i)):Q(Object(i)).forEach(function(n){Object.defineProperty(e,n,Object.getOwnPropertyDescriptor(i,n))})}return e}function Qt(e,t,i){return(t=Jt(t))in e?Object.defineProperty(e,t,{value:i,enumerable:!0,configurable:!0,writable:!0}):e[t]=i,e}function Jt(e){var t=te(e,"string");return O(t)=="symbol"?t:t+""}function te(e,t){if(O(e)!="object"||!e)return e;var i=e[Symbol.toPrimitive];if(i!==void 0){var n=i.call(e,t||"default");if(O(n)!="object")return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return(t==="string"?String:Number)(e)}var dt={name:"GalleriaContent",hostName:"Galleria",extends:N,inheritAttrs:!1,interval:null,emits:["activeitem-change","mask-hide"],data:function(){return{id:this.$attrs.id||E(),activeIndex:this.$attrs.activeIndex,numVisible:this.$attrs.numVisible,slideShowActive:!1}},watch:{"$attrs.id":function(t){this.id=t||E()},"$attrs.value":function(t){t&&t.length<this.numVisible&&(this.numVisible=t.length)},"$attrs.activeIndex":function(t){this.activeIndex=t},"$attrs.numVisible":function(t){this.numVisible=t},"$attrs.autoPlay":function(t){t?this.startSlideShow():this.stopSlideShow()}},mounted:function(){this.id=this.id||E()},updated:function(){this.$emit("activeitem-change",this.activeIndex)},beforeUnmount:function(){this.slideShowActive&&this.stopSlideShow()},methods:{getPTOptions:function(t){return this.ptm(t,{props:J(J({},this.$attrs),{},{pt:this.pt,unstyled:this.unstyled})})},isAutoPlayActive:function(){return this.slideShowActive},startSlideShow:function(){var t=this;this.interval=setInterval(function(){var i=t.$attrs.circular&&t.$attrs.value.length-1===t.activeIndex?0:t.activeIndex+1;t.activeIndex=i},this.$attrs.transitionInterval),this.slideShowActive=!0},stopSlideShow:function(){this.interval&&clearInterval(this.interval),this.slideShowActive=!1},getPositionClass:function(t,i){var n=["top","left","bottom","right"],r=n.find(function(a){return a===i});return r?"".concat(t,"-").concat(r):""},isVertical:function(){return this.$attrs.thumbnailsPosition==="left"||this.$attrs.thumbnailsPosition==="right"}},computed:{closeAriaLabel:function(){return this.$primevue.config.locale.aria?this.$primevue.config.locale.aria.close:void 0}},components:{GalleriaItem:ct,GalleriaThumbnails:ut,TimesIcon:gt},directives:{ripple:D}};function L(e){"@babel/helpers - typeof";return L=typeof Symbol=="function"&&typeof Symbol.iterator=="symbol"?function(t){return typeof t}:function(t){return t&&typeof Symbol=="function"&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t},L(e)}function tt(e,t){var i=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter(function(r){return Object.getOwnPropertyDescriptor(e,r).enumerable})),i.push.apply(i,n)}return i}function et(e){for(var t=1;t<arguments.length;t++){var i=arguments[t]!=null?arguments[t]:{};t%2?tt(Object(i),!0).forEach(function(n){ee(e,n,i[n])}):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(i)):tt(Object(i)).forEach(function(n){Object.defineProperty(e,n,Object.getOwnPropertyDescriptor(i,n))})}return e}function ee(e,t,i){return(t=ie(t))in e?Object.defineProperty(e,t,{value:i,enumerable:!0,configurable:!0,writable:!0}):e[t]=i,e}function ie(e){var t=ne(e,"string");return L(t)=="symbol"?t:t+""}function ne(e,t){if(L(e)!="object"||!e)return e;var i=e[Symbol.toPrimitive];if(i!==void 0){var n=i.call(e,t||"default");if(L(n)!="object")return n;throw new TypeError("@@toPrimitive must return a primitive value.")}return(t==="string"?String:Number)(e)}var ae=["id","aria-label","aria-roledescription"],re=["aria-label"],oe=["aria-live"];function le(e,t,i,n,r,a){var h=A("GalleriaItem"),c=A("GalleriaThumbnails"),o=$("ripple");return e.$attrs.value&&e.$attrs.value.length>0?(l(),u("div",s({key:0,id:r.id,role:"region",class:[e.cx("root"),e.$attrs.containerClass],style:e.$attrs.containerStyle,"aria-label":e.$attrs.ariaLabel,"aria-roledescription":e.$attrs.ariaRoledescription},et(et({},e.$attrs.containerProps),a.getPTOptions("root"))),[e.$attrs.fullScreen?T((l(),u("button",s({key:0,autofocus:"",type:"button",class:e.cx("closeButton"),"aria-label":a.closeAriaLabel,onClick:t[0]||(t[0]=function(p){return e.$emit("mask-hide")})},a.getPTOptions("closeButton")),[(l(),f(v(e.$attrs.templates.closeicon||"TimesIcon"),s({class:e.cx("closeIcon")},a.getPTOptions("closeIcon")),null,16,["class"]))],16,re)),[[o]]):m("",!0),e.$attrs.templates&&e.$attrs.templates.header?(l(),u("div",s({key:1,class:e.cx("header")},a.getPTOptions("header")),[(l(),f(v(e.$attrs.templates.header)))],16)):m("",!0),d("div",s({class:e.cx("content"),"aria-live":e.$attrs.autoPlay?"polite":"off"},a.getPTOptions("content")),[w(h,{id:r.id,activeIndex:r.activeIndex,"onUpdate:activeIndex":t[1]||(t[1]=function(p){return r.activeIndex=p}),slideShowActive:r.slideShowActive,"onUpdate:slideShowActive":t[2]||(t[2]=function(p){return r.slideShowActive=p}),value:e.$attrs.value,circular:e.$attrs.circular,templates:e.$attrs.templates,showIndicators:e.$attrs.showIndicators,changeItemOnIndicatorHover:e.$attrs.changeItemOnIndicatorHover,showItemNavigators:e.$attrs.showItemNavigators,autoPlay:e.$attrs.autoPlay,onStartSlideshow:a.startSlideShow,onStopSlideshow:a.stopSlideShow,pt:e.pt,unstyled:e.unstyled},null,8,["id","activeIndex","slideShowActive","value","circular","templates","showIndicators","changeItemOnIndicatorHover","showItemNavigators","autoPlay","onStartSlideshow","onStopSlideshow","pt","unstyled"]),e.$attrs.showThumbnails?(l(),f(c,{key:0,activeIndex:r.activeIndex,"onUpdate:activeIndex":t[3]||(t[3]=function(p){return r.activeIndex=p}),slideShowActive:r.slideShowActive,"onUpdate:slideShowActive":t[4]||(t[4]=function(p){return r.slideShowActive=p}),containerId:r.id,value:e.$attrs.value,templates:e.$attrs.templates,numVisible:r.numVisible,responsiveOptions:e.$attrs.responsiveOptions,circular:e.$attrs.circular,isVertical:a.isVertical(),contentHeight:e.$attrs.verticalThumbnailViewPortHeight,showThumbnailNavigators:e.$attrs.showThumbnailNavigators,prevButtonProps:e.$attrs.prevButtonProps,nextButtonProps:e.$attrs.nextButtonProps,onStopSlideshow:a.stopSlideShow,pt:e.pt,unstyled:e.unstyled},null,8,["activeIndex","slideShowActive","containerId","value","templates","numVisible","responsiveOptions","circular","isVertical","contentHeight","showThumbnailNavigators","prevButtonProps","nextButtonProps","onStopSlideshow","pt","unstyled"])):m("",!0)],16,oe),e.$attrs.templates&&e.$attrs.templates.footer?(l(),u("div",s({key:2,class:e.cx("footer")},a.getPTOptions("footer")),[(l(),f(v(e.$attrs.templates.footer)))],16)):m("",!0)],16,ae)):m("",!0)}dt.render=le;var ht={name:"Galleria",extends:At,inheritAttrs:!1,emits:["update:activeIndex","update:visible"],container:null,mask:null,data:function(){return{containerVisible:this.visible}},updated:function(){this.fullScreen&&this.visible&&(this.containerVisible=this.visible)},beforeUnmount:function(){this.fullScreen&&Z(),this.mask=null,this.container&&(K.clear(this.container),this.container=null)},methods:{onBeforeEnter:function(t){K.set("modal",t,this.baseZIndex||this.$primevue.config.zIndex.modal)},onEnter:function(t){this.mask.style.zIndex=String(parseInt(t.style.zIndex,10)-1),vt(),this.focus()},onBeforeLeave:function(){!this.isUnstyled&&W(this.mask,"p-overlay-mask-leave")},onAfterLeave:function(t){K.clear(t),this.containerVisible=!1,Z()},onActiveItemChange:function(t){this.activeIndex!==t&&this.$emit("update:activeIndex",t)},maskHide:function(){this.$emit("update:visible",!1)},containerRef:function(t){this.container=t},maskRef:function(t){this.mask=t},focus:function(){var t=this.container.$el.querySelector("[autofocus]");t&&t.focus()}},components:{GalleriaContent:dt,Portal:bt},directives:{focustrap:It}},se=["aria-modal"];function ce(e,t,i,n,r,a){var h=A("GalleriaContent"),c=A("Portal"),o=$("focustrap");return e.fullScreen?(l(),f(c,{key:0},{default:x(function(){return[r.containerVisible?(l(),u("div",s({key:0,ref:a.maskRef,class:[e.cx("mask"),e.maskClass],role:"dialog","aria-modal":e.fullScreen?"true":void 0},e.ptm("mask")),[w(yt,s({name:"p-galleria",onBeforeEnter:a.onBeforeEnter,onEnter:a.onEnter,onBeforeLeave:a.onBeforeLeave,onAfterLeave:a.onAfterLeave,appear:""},e.ptm("transition")),{default:x(function(){return[e.visible?T((l(),f(h,s({key:0,ref:a.containerRef,onMaskHide:a.maskHide,templates:e.$slots,onActiveitemChange:a.onActiveItemChange,pt:e.pt,unstyled:e.unstyled},e.$props),null,16,["onMaskHide","templates","onActiveitemChange","pt","unstyled"])),[[o]]):m("",!0)]}),_:1},16,["onBeforeEnter","onEnter","onBeforeLeave","onAfterLeave"])],16,se)):m("",!0)]}),_:1})):(l(),f(h,s({key:1,templates:e.$slots,onActiveitemChange:a.onActiveItemChange,pt:e.pt,unstyled:e.unstyled},e.$props),null,16,["templates","onActiveitemChange","pt","unstyled"]))}ht.render=ce;var ue=function(t){var i=t.dt;return`
.p-carousel {
    display: flex;
    flex-direction: column;
}

.p-carousel-content-container {
    display: flex;
    flex-direction: column;
    overflow: auto;
}

.p-carousel-content {
    display: flex;
    flex-direction: row;
    gap: `.concat(i("carousel.content.gap"),`;
}

.p-carousel-content:dir(rtl) {
    flex-direction: row-reverse;
}

.p-carousel-viewport {
    overflow: hidden;
    width: 100%;
}

.p-carousel-item-list {
    display: flex;
    flex-direction: row;
}

.p-carousel-item-list:dir(rtl) {
    flex-direction: row-reverse;
}

.p-carousel-prev-button,
.p-carousel-next-button {
    align-self: center;
    flex-shrink: 0;
}

.p-carousel-indicator-list {
    display: flex;
    flex-direction: row;
    justify-content: center;
    flex-wrap: wrap;
    padding: `).concat(i("carousel.indicator.list.padding"),`;
    gap: `).concat(i("carousel.indicator.list.gap"),`;
    margin: 0;
    list-style: none;
}

.p-carousel-indicator-button {
    display: flex;
    align-items: center;
    justify-content: center;
    background: `).concat(i("carousel.indicator.background"),`;
    width: `).concat(i("carousel.indicator.width"),`;
    height: `).concat(i("carousel.indicator.height"),`;
    border: 0 none;
    transition: background `).concat(i("carousel.transition.duration"),", color ").concat(i("carousel.transition.duration"),", outline-color ").concat(i("carousel.transition.duration"),", box-shadow ").concat(i("carousel.transition.duration"),`;
    outline-color: transparent;
    border-radius: `).concat(i("carousel.indicator.border.radius"),`;
    padding: 0;
    margin: 0;
    user-select: none;
    cursor: pointer;
}

.p-carousel-indicator-button:focus-visible {
    box-shadow: `).concat(i("carousel.indicator.focus.ring.shadow"),`;
    outline: `).concat(i("carousel.indicator.focus.ring.width")," ").concat(i("carousel.indicator.focus.ring.style")," ").concat(i("carousel.indicator.focus.ring.color"),`;
    outline-offset: `).concat(i("carousel.indicator.focus.ring.offset"),`;
}

.p-carousel-indicator-button:hover {
    background: `).concat(i("carousel.indicator.hover.background"),`;
}

.p-carousel-indicator-active .p-carousel-indicator-button {
    background: `).concat(i("carousel.indicator.active.background"),`;
}

.p-carousel-vertical .p-carousel-content {
    flex-direction: column;
}

.p-carousel-vertical .p-carousel-item-list {
    flex-direction: column;
    height: 100%;
}

.p-items-hidden .p-carousel-item {
    visibility: hidden;
}

.p-items-hidden .p-carousel-item.p-carousel-item-active {
    visibility: visible;
}
`)},de={root:function(t){var i=t.instance;return["p-carousel p-component",{"p-carousel-vertical":i.isVertical(),"p-carousel-horizontal":!i.isVertical()}]},header:"p-carousel-header",contentContainer:"p-carousel-content-container",content:"p-carousel-content",pcPrevButton:function(t){var i=t.instance;return["p-carousel-prev-button",{"p-disabled":i.backwardIsDisabled}]},viewport:"p-carousel-viewport",itemList:"p-carousel-item-list",itemClone:function(t){var i=t.index,n=t.value,r=t.totalShiftedItems,a=t.d_numVisible;return["p-carousel-item p-carousel-item-clone",{"p-carousel-item-active":r*-1===n.length+a,"p-carousel-item-start":i===0,"p-carousel-item-end":n.slice(-1*a).length-1===i}]},item:function(t){var i=t.instance,n=t.index;return["p-carousel-item",{"p-carousel-item-active":i.firstIndex()<=n&&i.lastIndex()>=n,"p-carousel-item-start":i.firstIndex()===n,"p-carousel-item-end":i.lastIndex()===n}]},pcNextButton:function(t){var i=t.instance;return["p-carousel-next-button",{"p-disabled":i.forwardIsDisabled}]},indicatorList:"p-carousel-indicator-list",indicator:function(t){var i=t.instance,n=t.index;return["p-carousel-indicator",{"p-carousel-indicator-active":i.d_page===n}]},indicatorButton:"p-carousel-indicator-button",footer:"p-carousel-footer"},he=it.extend({name:"carousel",theme:ue,classes:de}),me={name:"BaseCarousel",extends:N,props:{value:null,page:{type:Number,default:0},numVisible:{type:Number,default:1},numScroll:{type:Number,default:1},responsiveOptions:Array,orientation:{type:String,default:"horizontal"},verticalViewPortHeight:{type:String,default:"300px"},contentClass:String,containerClass:String,indicatorsContentClass:String,circular:{type:Boolean,default:!1},autoplayInterval:{type:Number,default:0},showNavigators:{type:Boolean,default:!0},showIndicators:{type:Boolean,default:!0},prevButtonProps:{type:Object,default:function(){return{severity:"secondary",text:!0,rounded:!0}}},nextButtonProps:{type:Object,default:function(){return{severity:"secondary",text:!0,rounded:!0}}}},style:he,provide:function(){return{$pcCarousel:this,$parentInstance:this}}};function k(e){return ve(e)||ge(e)||fe(e)||pe()}function pe(){throw new TypeError(`Invalid attempt to spread non-iterable instance.
In order to be iterable, non-array objects must have a [Symbol.iterator]() method.`)}function fe(e,t){if(e){if(typeof e=="string")return U(e,t);var i={}.toString.call(e).slice(8,-1);return i==="Object"&&e.constructor&&(i=e.constructor.name),i==="Map"||i==="Set"?Array.from(e):i==="Arguments"||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(i)?U(e,t):void 0}}function ge(e){if(typeof Symbol<"u"&&e[Symbol.iterator]!=null||e["@@iterator"]!=null)return Array.from(e)}function ve(e){if(Array.isArray(e))return U(e)}function U(e,t){(t==null||t>e.length)&&(t=e.length);for(var i=0,n=Array(t);i<t;i++)n[i]=e[i];return n}var mt={name:"Carousel",extends:me,inheritAttrs:!1,emits:["update:page"],isRemainingItemsAdded:!1,data:function(){return{remainingItems:0,d_numVisible:this.numVisible,d_numScroll:this.numScroll,d_oldNumScroll:0,d_oldNumVisible:0,d_oldValue:null,d_page:this.page,totalShiftedItems:this.page*this.numScroll*-1,allowAutoplay:!!this.autoplayInterval,d_circular:this.circular||this.allowAutoplay,swipeThreshold:20}},watch:{page:function(t){t>this.d_page?this.navForward({},t):t<this.d_page&&this.navBackward({},t),this.d_page=t},circular:function(t){this.d_circular=t},numVisible:function(t,i){this.d_numVisible=t,this.d_oldNumVisible=i},numScroll:function(t,i){this.d_oldNumScroll=i,this.d_numScroll=t},value:function(t){this.d_oldValue=t}},mounted:function(){var t=!1;if(this.createStyle(),this.calculatePosition(),this.responsiveOptions&&this.bindDocumentListeners(),this.isCircular()){var i=this.totalShiftedItems;this.d_page===0?i=-1*this.d_numVisible:i===0&&(i=-1*this.value.length,this.remainingItems>0&&(this.isRemainingItemsAdded=!0)),i!==this.totalShiftedItems&&(this.totalShiftedItems=i,t=!0)}!t&&this.isAutoplay()&&this.startAutoplay()},updated:function(){if(!this.empty){var t=this.isCircular(),i=!1,n=this.totalShiftedItems;if(this.autoplayInterval&&this.stopAutoplay(),this.d_oldNumScroll!==this.d_numScroll||this.d_oldNumVisible!==this.d_numVisible||this.d_oldValue.length!==this.value.length){this.remainingItems=(this.value.length-this.d_numVisible)%this.d_numScroll;var r=this.d_page;this.totalIndicators!==0&&r>=this.totalIndicators&&(r=this.totalIndicators-1,this.$emit("update:page",r),this.d_page=r,i=!0),n=r*this.d_numScroll*-1,t&&(n-=this.d_numVisible),r===this.totalIndicators-1&&this.remainingItems>0?(n+=-1*this.remainingItems+this.d_numScroll,this.isRemainingItemsAdded=!0):this.isRemainingItemsAdded=!1,n!==this.totalShiftedItems&&(this.totalShiftedItems=n,i=!0),this.d_oldNumScroll=this.d_numScroll,this.d_oldNumVisible=this.d_numVisible,this.d_oldValue=this.value,this.$refs.itemsContainer.style.transform=this.isVertical()?"translate3d(0, ".concat(n*(100/this.d_numVisible),"%, 0)"):"translate3d(".concat(n*(100/this.d_numVisible),"%, 0, 0)")}t&&(this.d_page===0?n=-1*this.d_numVisible:n===0&&(n=-1*this.value.length,this.remainingItems>0&&(this.isRemainingItemsAdded=!0)),n!==this.totalShiftedItems&&(this.totalShiftedItems=n,i=!0)),!i&&this.isAutoplay()&&this.startAutoplay()}},beforeUnmount:function(){this.responsiveOptions&&this.unbindDocumentListeners(),this.autoplayInterval&&this.stopAutoplay()},methods:{getIndicatorPTOptions:function(t,i){return this.ptm(t,{context:{highlighted:i===this.d_page}})},getItemPTOptions:function(t,i){return this.ptm(t,{context:{index:i,active:this.firstIndex()<=i&&this.lastIndex()>=i,start:this.firstIndex()===i,end:this.lastIndex()===i}})},step:function(t,i){var n=this.totalShiftedItems,r=this.isCircular();if(i!=null)n=this.d_numScroll*i*-1,r&&(n-=this.d_numVisible),this.isRemainingItemsAdded=!1;else{n+=this.d_numScroll*t,this.isRemainingItemsAdded&&(n+=this.remainingItems-this.d_numScroll*t,this.isRemainingItemsAdded=!1);var a=r?n+this.d_numVisible:n;i=Math.abs(Math.floor(a/this.d_numScroll))}r&&this.d_page===this.totalIndicators-1&&t===-1?(n=-1*(this.value.length+this.d_numVisible),i=0):r&&this.d_page===0&&t===1?(n=0,i=this.totalIndicators-1):i===this.totalIndicators-1&&this.remainingItems>0&&(n+=this.remainingItems*-1-this.d_numScroll*t,this.isRemainingItemsAdded=!0),this.$refs.itemsContainer&&(!this.isUnstyled&&R(this.$refs.itemsContainer,"p-items-hidden"),this.$refs.itemsContainer.style.transform=this.isVertical()?"translate3d(0, ".concat(n*(100/this.d_numVisible),"%, 0)"):"translate3d(".concat(n*(100/this.d_numVisible),"%, 0, 0)"),this.$refs.itemsContainer.style.transition="transform 500ms ease 0s"),this.totalShiftedItems=n,this.$emit("update:page",i),this.d_page=i},calculatePosition:function(){if(this.$refs.itemsContainer&&this.responsiveOptions){for(var t=window.innerWidth,i={numVisible:this.numVisible,numScroll:this.numScroll},n=0;n<this.responsiveOptions.length;n++){var r=this.responsiveOptions[n];parseInt(r.breakpoint,10)>=t&&(i=r)}if(this.d_numScroll!==i.numScroll){var a=this.d_page;a=parseInt(a*this.d_numScroll/i.numScroll),this.totalShiftedItems=i.numScroll*a*-1,this.isCircular()&&(this.totalShiftedItems-=i.numVisible),this.d_numScroll=i.numScroll,this.$emit("update:page",a),this.d_page=a}this.d_numVisible!==i.numVisible&&(this.d_numVisible=i.numVisible)}},navBackward:function(t,i){(this.d_circular||this.d_page!==0)&&this.step(1,i),this.allowAutoplay=!1,t.cancelable&&t.preventDefault()},navForward:function(t,i){(this.d_circular||this.d_page<this.totalIndicators-1)&&this.step(-1,i),this.allowAutoplay=!1,t.cancelable&&t.preventDefault()},onIndicatorClick:function(t,i){var n=this.d_page;i>n?this.navForward(t,i):i<n&&this.navBackward(t,i)},onTransitionEnd:function(){this.$refs.itemsContainer&&(!this.isUnstyled&&W(this.$refs.itemsContainer,"p-items-hidden"),this.$refs.itemsContainer.style.transition="",(this.d_page===0||this.d_page===this.totalIndicators-1)&&this.isCircular()&&(this.$refs.itemsContainer.style.transform=this.isVertical()?"translate3d(0, ".concat(this.totalShiftedItems*(100/this.d_numVisible),"%, 0)"):"translate3d(".concat(this.totalShiftedItems*(100/this.d_numVisible),"%, 0, 0)")))},onTouchStart:function(t){var i=t.changedTouches[0];this.startPos={x:i.pageX,y:i.pageY}},onTouchMove:function(t){var i=t.changedTouches[0],n=this.isVertical()?i.pageY-this.startPos.y:i.pageX-this.startPos.x;Math.abs(n)>this.swipeThreshold&&t.cancelable&&t.preventDefault()},onTouchEnd:function(t){var i=t.changedTouches[0];this.isVertical()?this.changePageOnTouch(t,i.pageY-this.startPos.y):this.changePageOnTouch(t,i.pageX-this.startPos.x)},changePageOnTouch:function(t,i){Math.abs(i)>this.swipeThreshold&&(i<0?this.navForward(t):this.navBackward(t))},onIndicatorKeydown:function(t){switch(t.code){case"ArrowRight":this.onRightKey();break;case"ArrowLeft":this.onLeftKey();break;case"Home":this.onHomeKey(),t.preventDefault();break;case"End":this.onEndKey(),t.preventDefault();break;case"ArrowUp":case"ArrowDown":case"PageUp":case"PageDown":t.preventDefault();break;case"Tab":this.onTabKey();break}},onRightKey:function(){var t=k(g(this.$refs.indicatorContent,'[data-pc-section="indicator"]')),i=this.findFocusedIndicatorIndex();this.changedFocusedIndicator(i,i+1===t.length?t.length-1:i+1)},onLeftKey:function(){var t=this.findFocusedIndicatorIndex();this.changedFocusedIndicator(t,t-1<=0?0:t-1)},onHomeKey:function(){var t=this.findFocusedIndicatorIndex();this.changedFocusedIndicator(t,0)},onEndKey:function(){var t=k(g(this.$refs.indicatorContent,'[data-pc-section="indicator"]')),i=this.findFocusedIndicatorIndex();this.changedFocusedIndicator(i,t.length-1)},onTabKey:function(){var t=k(g(this.$refs.indicatorContent,'[data-pc-section="indicator"]')),i=t.findIndex(function(a){return G(a,"data-p-active")===!0}),n=P(this.$refs.indicatorContent,'[data-pc-section="indicator"] > button[tabindex="0"]'),r=t.findIndex(function(a){return a===n.parentElement});t[r].children[0].tabIndex="-1",t[i].children[0].tabIndex="0"},findFocusedIndicatorIndex:function(){var t=k(g(this.$refs.indicatorContent,'[data-pc-section="indicator"]')),i=P(this.$refs.indicatorContent,'[data-pc-section="indicator"] > button[tabindex="0"]');return t.findIndex(function(n){return n===i.parentElement})},changedFocusedIndicator:function(t,i){var n=k(g(this.$refs.indicatorContent,'[data-pc-section="indicator"]'));n[t].children[0].tabIndex="-1",n[i].children[0].tabIndex="0",n[i].children[0].focus()},bindDocumentListeners:function(){var t=this;this.documentResizeListener||(this.documentResizeListener=function(i){t.calculatePosition(i)},window.addEventListener("resize",this.documentResizeListener))},unbindDocumentListeners:function(){this.documentResizeListener&&(window.removeEventListener("resize",this.documentResizeListener),this.documentResizeListener=null)},startAutoplay:function(){var t=this;this.interval=setInterval(function(){t.d_page===t.totalIndicators-1?t.step(-1,0):t.step(-1,t.d_page+1)},this.autoplayInterval)},stopAutoplay:function(){this.interval&&clearInterval(this.interval)},createStyle:function(){if(!this.carouselStyle){var t;this.carouselStyle=document.createElement("style"),this.carouselStyle.type="text/css",nt(this.carouselStyle,"nonce",(t=this.$primevue)===null||t===void 0||(t=t.config)===null||t===void 0||(t=t.csp)===null||t===void 0?void 0:t.nonce),document.body.appendChild(this.carouselStyle)}var i=`
                .p-carousel[`.concat(this.$attrSelector,`] .p-carousel-item {
                    flex: 1 0 `).concat(100/this.d_numVisible,`%
                }
            `);if(this.responsiveOptions&&!this.isUnstyled){var n=k(this.responsiveOptions),r=at();n.sort(function(c,o){var p=c.breakpoint,b=o.breakpoint;return rt(p,b,-1,r)});for(var a=0;a<n.length;a++){var h=n[a];i+=`
                        @media screen and (max-width: `.concat(h.breakpoint,`) {
                            .p-carousel[`).concat(this.$attrSelector,`] .p-carousel-item {
                                flex: 1 0 `).concat(100/h.numVisible,`%
                            }
                        }
                    `)}}this.carouselStyle.innerHTML=i},isVertical:function(){return this.orientation==="vertical"},hasValidItemCount:function(){return this.value&&this.value.length>this.d_numVisible},isCircular:function(){return this.hasValidItemCount()&&this.d_circular},isAutoplay:function(){return this.hasValidItemCount()&&this.autoplayInterval&&this.allowAutoplay},firstIndex:function(){return this.isCircular()?-1*(this.totalShiftedItems+this.d_numVisible):this.totalShiftedItems*-1},lastIndex:function(){return this.firstIndex()+this.d_numVisible-1},ariaSlideNumber:function(t){return this.$primevue.config.locale.aria?this.$primevue.config.locale.aria.slideNumber.replace(/{slideNumber}/g,t):void 0},ariaPageLabel:function(t){return this.$primevue.config.locale.aria?this.$primevue.config.locale.aria.pageLabel.replace(/{page}/g,t):void 0}},computed:{totalIndicators:function(){return this.value?Math.max(Math.ceil((this.value.length-this.d_numVisible)/this.d_numScroll)+1,0):0},backwardIsDisabled:function(){return this.value&&(!this.circular||this.value.length<this.d_numVisible)&&this.d_page===0},forwardIsDisabled:function(){return this.value&&(!this.circular||this.value.length<this.d_numVisible)&&(this.d_page===this.totalIndicators-1||this.totalIndicators===0)},ariaSlideLabel:function(){return this.$primevue.config.locale.aria?this.$primevue.config.locale.aria.slide:void 0},ariaPrevButtonLabel:function(){return this.$primevue.config.locale.aria?this.$primevue.config.locale.aria.prevPageLabel:void 0},ariaNextButtonLabel:function(){return this.$primevue.config.locale.aria?this.$primevue.config.locale.aria.nextPageLabel:void 0},empty:function(){return!this.value||this.value.length===0},emptyMessageText:function(){var t;return((t=this.$primevue.config)===null||t===void 0||(t=t.locale)===null||t===void 0?void 0:t.emptyMessage)||""}},components:{Button:st,ChevronRightIcon:Y,ChevronDownIcon:ot,ChevronLeftIcon:X,ChevronUpIcon:lt},directives:{ripple:D}},be=["aria-live"],Ie=["data-p-carousel-item-active","data-p-carousel-item-start","data-p-carousel-item-end"],ye=["aria-hidden","aria-label","aria-roledescription","data-p-carousel-item-active","data-p-carousel-item-start","data-p-carousel-item-end"],we=["data-p-active"],Se=["tabindex","aria-label","aria-current","onClick"];function xe(e,t,i,n,r,a){var h=A("Button");return l(),u("div",s({class:e.cx("root"),role:"region"},e.ptmi("root")),[e.$slots.header?(l(),u("div",s({key:0,class:e.cx("header")},e.ptm("header")),[y(e.$slots,"header")],16)):m("",!0),a.empty?y(e.$slots,"empty",{key:2},function(){return[wt(M(a.emptyMessageText),1)]}):(l(),u("div",s({key:1,class:[e.cx("contentContainer"),e.containerClass]},e.ptm("contentContainer")),[d("div",s({class:[e.cx("content"),e.contentClass],"aria-live":r.allowAutoplay?"polite":"off"},e.ptm("content")),[e.showNavigators?(l(),f(h,s({key:0,class:e.cx("pcPrevButton"),disabled:a.backwardIsDisabled,"aria-label":a.ariaPrevButtonLabel,unstyled:e.unstyled,onClick:a.navBackward},e.prevButtonProps,{pt:e.ptm("pcPrevButton"),"data-pc-group-section":"navigator"}),{icon:x(function(c){return[y(e.$slots,"previcon",{},function(){return[(l(),f(v(a.isVertical()?"ChevronUpIcon":"ChevronLeftIcon"),s({class:c.icon},e.ptm("pcPrevButton").icon),null,16,["class"]))]})]}),_:3},16,["class","disabled","aria-label","unstyled","onClick","pt"])):m("",!0),d("div",s({class:e.cx("viewport"),style:[{height:a.isVertical()?e.verticalViewPortHeight:"auto"}],onTouchend:t[1]||(t[1]=function(){return a.onTouchEnd&&a.onTouchEnd.apply(a,arguments)}),onTouchstart:t[2]||(t[2]=function(){return a.onTouchStart&&a.onTouchStart.apply(a,arguments)}),onTouchmove:t[3]||(t[3]=function(){return a.onTouchMove&&a.onTouchMove.apply(a,arguments)})},e.ptm("viewport")),[d("div",s({ref:"itemsContainer",class:e.cx("itemList"),onTransitionend:t[0]||(t[0]=function(){return a.onTransitionEnd&&a.onTransitionEnd.apply(a,arguments)})},e.ptm("itemList")),[a.isCircular()?(l(!0),u(S,{key:0},C(e.value.slice(-1*r.d_numVisible),function(c,o){return l(),u("div",s({key:o+"_scloned",class:e.cx("itemClone",{index:o,value:e.value,totalShiftedItems:r.totalShiftedItems,d_numVisible:r.d_numVisible}),ref_for:!0},e.ptm("itemClone"),{"data-p-carousel-item-active":r.totalShiftedItems*-1===e.value.length+r.d_numVisible,"data-p-carousel-item-start":o===0,"data-p-carousel-item-end":e.value.slice(-1*r.d_numVisible).length-1===o}),[y(e.$slots,"item",{data:c,index:o})],16,Ie)}),128)):m("",!0),(l(!0),u(S,null,C(e.value,function(c,o){return l(),u("div",s({key:o,class:e.cx("item",{index:o}),role:"group","aria-hidden":a.firstIndex()>o||a.lastIndex()<o?!0:void 0,"aria-label":a.ariaSlideNumber(o),"aria-roledescription":a.ariaSlideLabel,ref_for:!0},a.getItemPTOptions("item",o),{"data-p-carousel-item-active":a.firstIndex()<=o&&a.lastIndex()>=o,"data-p-carousel-item-start":a.firstIndex()===o,"data-p-carousel-item-end":a.lastIndex()===o}),[y(e.$slots,"item",{data:c,index:o})],16,ye)}),128)),a.isCircular()?(l(!0),u(S,{key:1},C(e.value.slice(0,r.d_numVisible),function(c,o){return l(),u("div",s({key:o+"_fcloned",class:e.cx("itemClone",{index:o,value:e.value,totalShiftedItems:r.totalShiftedItems,d_numVisible:r.d_numVisible}),ref_for:!0},e.ptm("itemClone")),[y(e.$slots,"item",{data:c,index:o})],16)}),128)):m("",!0)],16)],16),e.showNavigators?(l(),f(h,s({key:1,class:e.cx("pcNextButton"),disabled:a.forwardIsDisabled,"aria-label":a.ariaNextButtonLabel,unstyled:e.unstyled,onClick:a.navForward},e.nextButtonProps,{pt:e.ptm("pcNextButton"),"data-pc-group-section":"navigator"}),{icon:x(function(c){return[y(e.$slots,"nexticon",{},function(){return[(l(),f(v(a.isVertical()?"ChevronDownIcon":"ChevronRightIcon"),s({class:c.class},e.ptm("pcNextButton").icon),null,16,["class"]))]})]}),_:3},16,["class","disabled","aria-label","unstyled","onClick","pt"])):m("",!0)],16,be),a.totalIndicators>=0&&e.showIndicators?(l(),u("ul",s({key:0,ref:"indicatorContent",class:[e.cx("indicatorList"),e.indicatorsContentClass],onKeydown:t[4]||(t[4]=function(){return a.onIndicatorKeydown&&a.onIndicatorKeydown.apply(a,arguments)})},e.ptm("indicatorList")),[(l(!0),u(S,null,C(a.totalIndicators,function(c,o){return l(),u("li",s({key:"p-carousel-indicator-"+o.toString(),class:e.cx("indicator",{index:o}),ref_for:!0},a.getIndicatorPTOptions("indicator",o),{"data-p-active":r.d_page===o}),[d("button",s({class:e.cx("indicatorButton"),type:"button",tabindex:r.d_page===o?"0":"-1","aria-label":a.ariaPageLabel(o+1),"aria-current":r.d_page===o?"page":void 0,onClick:function(b){return a.onIndicatorClick(b,o)},ref_for:!0},a.getIndicatorPTOptions("indicatorButton",o)),null,16,Se)],16,we)}),128))],16)):m("",!0)],16)),e.$slots.footer?(l(),u("div",s({key:3,class:e.cx("footer")},e.ptm("footer")),[y(e.$slots,"footer")],16)):m("",!0)],16)}mt.render=xe;const ke={getData(){return[{itemImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria1.jpg",thumbnailImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria1s.jpg",alt:"Description for Image 1",title:"Title 1"},{itemImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria2.jpg",thumbnailImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria2s.jpg",alt:"Description for Image 2",title:"Title 2"},{itemImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria3.jpg",thumbnailImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria3s.jpg",alt:"Description for Image 3",title:"Title 3"},{itemImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria4.jpg",thumbnailImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria4s.jpg",alt:"Description for Image 4",title:"Title 4"},{itemImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria5.jpg",thumbnailImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria5s.jpg",alt:"Description for Image 5",title:"Title 5"},{itemImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria6.jpg",thumbnailImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria6s.jpg",alt:"Description for Image 6",title:"Title 6"},{itemImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria7.jpg",thumbnailImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria7s.jpg",alt:"Description for Image 7",title:"Title 7"},{itemImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria8.jpg",thumbnailImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria8s.jpg",alt:"Description for Image 8",title:"Title 8"},{itemImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria9.jpg",thumbnailImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria9s.jpg",alt:"Description for Image 9",title:"Title 9"},{itemImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria10.jpg",thumbnailImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria10s.jpg",alt:"Description for Image 10",title:"Title 10"},{itemImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria11.jpg",thumbnailImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria11s.jpg",alt:"Description for Image 11",title:"Title 11"},{itemImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria12.jpg",thumbnailImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria12s.jpg",alt:"Description for Image 12",title:"Title 12"},{itemImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria13.jpg",thumbnailImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria13s.jpg",alt:"Description for Image 13",title:"Title 13"},{itemImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria14.jpg",thumbnailImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria14s.jpg",alt:"Description for Image 14",title:"Title 14"},{itemImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria15.jpg",thumbnailImageSrc:"https://primefaces.org/cdn/primevue/images/galleria/galleria15s.jpg",alt:"Description for Image 15",title:"Title 15"}]},getImages(){return Promise.resolve(this.getData())}},Ce={class:"card"},Pe={class:"border border-surface-200 dark:border-surface-700 rounded m-2 p-4"},Te={class:"mb-4"},Ve={class:"relative mx-auto"},Ae=["src","alt"],Be={class:"dark:bg-surface-900 absolute rounded-border",style:{left:"5px",top:"5px"}},Oe={class:"mb-4 font-medium"},Le={class:"flex justify-between items-center"},Ne={class:"mt-0 font-semibold text-xl"},je={class:"card"},_e={class:"card"},De=["src","alt"],$e=["src","alt"],Xe={__name:"MediaDoc",setup(e){const t=j([]),i=j([]),n=j([{breakpoint:"1024px",numVisible:5},{breakpoint:"960px",numVisible:4},{breakpoint:"768px",numVisible:3},{breakpoint:"560px",numVisible:1}]),r=j([{breakpoint:"1024px",numVisible:3,numScroll:3},{breakpoint:"768px",numVisible:2,numScroll:2},{breakpoint:"560px",numVisible:1,numScroll:1}]);St(()=>{Ct.getProductsSmall().then(h=>t.value=h),ke.getImages().then(h=>i.value=h)});function a(h){switch(h){case"INSTOCK":return"success";case"LOWSTOCK":return"warning";case"OUTOFSTOCK":return"danger";default:return null}}return(h,c)=>{const o=kt,p=st,b=mt,pt=xt,ft=ht;return l(),u(S,null,[d("div",Ce,[c[0]||(c[0]=d("div",{class:"font-semibold text-xl mb-4"},"Carousel",-1)),w(b,{value:t.value,numVisible:3,numScroll:3,responsiveOptions:r.value},{item:x(I=>[d("div",Pe,[d("div",Te,[d("div",Ve,[d("img",{src:"https://primefaces.org/cdn/primevue/images/product/"+I.data.image,alt:I.data.name,class:"w-full rounded"},null,8,Ae),d("div",Be,[w(o,{value:I.data.inventoryStatus,severity:a(I.data.inventoryStatus)},null,8,["value","severity"])])])]),d("div",Oe,M(I.data.name),1),d("div",Le,[d("div",Ne,"$"+M(I.data.price),1),d("span",null,[w(p,{icon:"pi pi-heart",severity:"secondary",outlined:""}),w(p,{icon:"pi pi-shopping-cart",class:"ml-2"})])])])]),_:1},8,["value","responsiveOptions"])]),d("div",je,[c[1]||(c[1]=d("div",{class:"font-semibold text-xl mb-4"},"Image",-1)),w(pt,{src:"https://primefaces.org/cdn/primevue/images/galleria/galleria10.jpg",alt:"Image",width:"250"})]),d("div",_e,[c[2]||(c[2]=d("div",{class:"font-semibold text-xl mb-4"},"Galleria",-1)),w(ft,{value:i.value,responsiveOptions:n.value,numVisible:5,containerStyle:"max-width: 640px"},{item:x(I=>[d("img",{src:I.item.itemImageSrc,alt:I.item.alt,style:{width:"100%"}},null,8,De)]),thumbnail:x(I=>[d("img",{src:I.item.thumbnailImageSrc,alt:I.item.alt},null,8,$e)]),_:1},8,["value","responsiveOptions"])])],64)}}};export{Xe as default};
