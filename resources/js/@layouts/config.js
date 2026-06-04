import logo from '@/assets/logo-smart.png'
import { AppContentLayoutNav, ContentWidth, FooterType, HorizontalNavType, NavbarType } from '@layouts/enums'
import { breakpointsVuetify } from '@vueuse/core'

export const layoutConfig = {
  app: {
    title: 'VITRINE-INCUBADORA',
    logo: h('div', { style: 'display: flex; align-items: center; gap: 10px;' }, [
      h('img', { 
        src: logo, 
        alt: 'app-logo', 
        style: 'height: 40px; object-fit: contain;' 
      }),
      h('span', { class: 'app-logo-title', style: 'color: #007bff; text-transform: uppercase; font-weight: bold; font-size: 1.05rem; letter-spacing: 0.5px;' })
    ]),
    contentWidth: ContentWidth.Boxed,
    contentLayoutNav: AppContentLayoutNav.Vertical,
    overlayNavFromBreakpoint: breakpointsVuetify.md,

    // isRTL: false,
    i18n: {
      enable: true,
    },
    iconRenderer: h('div'),
  },
  navbar: {
    type: NavbarType.Sticky,
    navbarBlur: true,
  },
  footer: {
    type: FooterType.Static,
  },
  verticalNav: {
    isVerticalNavCollapsed: false,
    defaultNavItemIconProps: { icon: 'tabler-circle' },
  },
  horizontalNav: {
    type: HorizontalNavType.Sticky,
    transition: 'none',
    popoverOffset: 0,
  },
  icons: {
    chevronDown: { icon: 'tabler-chevron-down' },
    chevronRight: { icon: 'tabler-chevron-right' },
    close: { icon: 'tabler-x' },
    verticalNavPinned: { icon: 'tabler-circle-dot' },
    verticalNavUnPinned: { icon: 'tabler-circle' },
    sectionTitlePlaceholder: { icon: 'tabler-minus' },
  },
}
