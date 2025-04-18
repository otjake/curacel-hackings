<script setup>
import { ref, onMounted, onBeforeUnmount, watch } from "vue";
import { Head, router, Link, usePage } from "@inertiajs/vue3";
import { useFeature } from "@/composables/useFeature";
import { FEATURES } from "@/utils/features";
import Banner from "@/Components/Banner.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import ApplicationMark from "@/Components/ApplicationMark.vue";
import ActivityLogsIcon from "../../icons/activity-logs.svg";
import ActivityLogsIconActive from "../../icons/activity-logs-active.svg";
import HomeIcon from "../../icons/home.svg";
import HomeIconActive from "../../icons/home-active.svg";
import BeneficiaryIcon from "../../icons/beneficiary.svg";
import BeneficiaryIconActive from "../../icons/beneficiary-active.svg";
import CardIcon from "../../icons/card.svg";
import CardIconActive from "../../icons/card-active.svg";
import SettingsIcon from "../../icons/settings.svg";
import SettingsIconActive from "../../icons/settings-active.svg";
import ApprovalsIcon from "../../icons/approvals.svg";
import ApprovalsIconActive from "../../icons/approvals-active.svg";
import PayableIcon from "../../icons/payable.svg";
import PayableIconActive from "../../icons/payable-active.svg";
import CustomersIcon from "../../icons/customers.svg";
import CustomersIconActive from "../../icons/customers-active.svg";
import PaymentLinksIcon from "../../icons/payment-links.svg";
import PaymentLinksIconActive from "../../icons/payment-links-active.svg";
import InvoicesIcon from "../../icons/invoices.svg";
import InvoicesIconActive from "../../icons/invoices-active.svg";
import IntegrationsIcon from "../../icons/integrations.svg";
import IntegrationsIconActive from "../../icons/integrations-active.svg";
import TransactionsIcon from "../../icons/transaction.svg";
import TransactionsIconActive from "../../icons/transaction-active.svg";
import QuickPayIconActive from "../../icons/quick-pay-active.svg";
import QuickPayIcon from "../../icons/quick-pay.svg";
import PayoutOptionsModal from "@/Components/PayoutOptionsModal.vue";
import IdleLogoutModal from "@/Components/Modals/IdleLogoutModal.vue";

defineProps({
  title: String,
});

const page = usePage();

const initToggleWidget = () => {
  const { fusionauth_id } = page.props?.auth?.user ?? {}
  const url = import.meta.env.VITE_CURACEL_ORG_URL

  if (!fusionauth_id || !url) return;

  const link = document.createElement("link");
  link.rel = "stylesheet";
  link.href = "https://s3.us-east-1.amazonaws.com/org.curacel.co/toggle-widget/dist/toggle-widget.css";
  document.head.appendChild(link);

  const script = document.createElement('script');
  script.src = "https://s3.us-east-1.amazonaws.com/org.curacel.co/toggle-widget/dist/toggle-widget.iife.js";
  script.onload = () => {
    if (document.querySelector('#toggle-container')) {
      window.loadToggleWidget({
        selector: '#toggle-container',
        apiUrl: url,
        fusionAuthId: fusionauth_id,
        storageKey: "curacelTheme",
      });
    }
  }
  document.head.appendChild(script);
}

const getCurrentRouteStatus = (routeName) => {
  const currentRoute = route().current();

  return Array.isArray(routeName)
    ? routeName.includes(currentRoute)
    : currentRoute === routeName;
};

const showPayoutOptionsModal = ref(false);

// Idle Tracking
const idleDuration = 60 * 10;
const timer = ref(undefined);
const lastActivityTime = ref(Date.now());
const currentTime = ref(Date.now());
const idleModalVisible = ref(false);
const eventListenersActive = ref(true);

const handleInactivity = () => {
  currentTime.value = Date.now();
  if (currentTime.value - lastActivityTime.value >= idleDuration * 1000) {
    idleModalVisible.value = true;
    pauseActivityTracking();
  }
};

const resetActivity = () => {
  if (!idleModalVisible.value && eventListenersActive.value) {
    lastActivityTime.value = Date.now();
  }
};

const pauseActivityTracking = () => {
  eventListenersActive.value = false;
};

const resumeActivityTracking = () => {
  eventListenersActive.value = true;
  lastActivityTime.value = Date.now();
};

const stayLoggedIn = () => {
  resumeActivityTracking();
  idleModalVisible.value = false;
};

// Add nonce generation function
const generateNonce = () => {
  const unitArray = new Uint8Array(16);
  window.crypto.getRandomValues(unitArray);
  return btoa(String.fromCharCode.apply(null, unitArray));
};

// Function to load Zoho SalesIQ
const loadZohoSalesIQ = () => {
  var d = document;
  var s = d.createElement("script");
  s.type = "text/javascript";
  s.id = "zsiqscript";
  s.defer = true;
  s.nonce = generateNonce();
  s.src = "https://salesiq.zohopublic.com/widget?wc=siq5f0e519cb5bdc3d5204a6a6665e8503ae916a8e8348b3703c6f2d1532147105f"; // Replace with your actual SalesIQ widget URL

  var t = d.getElementsByTagName("script")[0];
  t.parentNode.insertBefore(s, t);

  window.$zoho = window.$zoho || {};
  $zoho.salesiq = $zoho.salesiq || { ready: function () {} };

};

loadZohoSalesIQ();

onMounted(() => {
  timer.value = setInterval(handleInactivity, 30000);

  ["mousemove", "keypress", "scroll", "keydown"].forEach((event) => {
    window.addEventListener(event, resetActivity);
  });

  initToggleWidget();
});

onBeforeUnmount(() => {
  clearInterval(timer.value);
  ["mousemove", "keypress", "scroll", "keydown"].forEach((event) => {
    window.removeEventListener(event, resetActivity);
  });
});

const iconSources = {
  dashboard: {
    active: HomeIconActive,
    default: HomeIcon,
  },
  beneficiaries: {
    active: BeneficiaryIconActive,
    default: BeneficiaryIcon,
  },
  payout: {
    active: CardIconActive,
    default: CardIcon,
  },
  settings: {
    active: SettingsIconActive,
    default: SettingsIcon,
  },
  approvals: {
    active: ApprovalsIconActive,
    default: ApprovalsIcon,
  },
  payable: {
    active: PayableIconActive,
    default: PayableIcon,
  },
  customers: {
    active: CustomersIconActive,
    default: CustomersIcon,
  },
  paymentLinks: {
    active: PaymentLinksIconActive,
    default: PaymentLinksIcon,
  },
  invoices: {
    active: InvoicesIconActive,
    default: InvoicesIcon,
  },
  integrations: {
    active: IntegrationsIconActive,
    default: IntegrationsIcon,
  },
  activitylogs: {
    active: ActivityLogsIconActive,
    default: ActivityLogsIcon,
  },
  transactions: {
    active: TransactionsIconActive,
    default: TransactionsIcon,
  },
  quickPay: {
    active: QuickPayIconActive,
    default: QuickPayIcon,
  },
};

const getIconSrc = (routeName, combinedCategory = null) => {
  let isCurrentRoute = getCurrentRouteStatus(routeName);
  const iconKey = combinedCategory || routeName;

  return isCurrentRoute
    ? iconSources[iconKey]?.active
    : iconSources[iconKey]?.default;
};

const getSidebarLinkClass = (routeName) => {
  let isCurrentRoute = getCurrentRouteStatus(routeName);

  return isCurrentRoute
    ? "bg-white dark:bg-gray-800 flex items-center p-2 text-black rounded-lg dark:text-white group font-medium"
    : "flex items-center p-4 text-gray-500 rounded-lg dark:text-white hover:bg-white dark:hover:bg-gray-700 group font-normal";
};

const getIconClass = (routeName) => {
  let isCurrentRoute = getCurrentRouteStatus(routeName);

  return isCurrentRoute ? "bg-icon-blue text-white rounded-full p-2" : "";
};

const showingNavigationDropdown = ref(false);

const logout = () => {
  axios
    .post(route("logout"))
    .then(({ data }) => {
      localStorage.removeItem("loginActivated");
      localStorage.removeItem("showWalletBalances");

      const redirectUrl = data.data.redirect_url;

      window.location.href = redirectUrl;
    })
    .catch((error) => {
      const errorMessage = error.response
        ? error.response.data.message
        : "An error occurred while attempting to log you out. Please try again.";

      ErrorToast.fire({
        text: errorMessage,
        timer: 3000,
      });
    });
};

const loginActivated = localStorage.getItem("loginActivated");

if (loginActivated) {
  const user = page.props.auth.user;
  window.posthog.identify(user.id, {
    email: user.email,
    name: user.name,
    organization: user.payer.name,
  });
}

watch(
  () => page.props.flash?.error,
  (error) => {
    if (error) {
      ErrorToast.fire({
        text: error,
      });
      page.props.flash.error = null;
    }
  },
  { immediate: true }
);

const { isEnabled } = useFeature();
const showPaymentLinks = isEnabled(FEATURES.PAYMENT_LINKS);
const showQuickPay = isEnabled(FEATURES.QUICK_PAY);
</script>

<template>
  <button
    data-drawer-target="sidebar"
    data-drawer-toggle="sidebar"
    aria-controls="sidebar"
    type="button"
    class="inline-flex items-center p-2 mt-2 ml-3 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
  >
    <span class="sr-only">Open sidebar</span>
    <svg
      class="w-6 h-6"
      aria-hidden="true"
      fill="currentColor"
      viewBox="0 0 20 20"
      xmlns="http://www.w3.org/2000/svg"
    >
      <path
        clip-rule="evenodd"
        fill-rule="evenodd"
        d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"
      ></path>
    </svg>
  </button>

  <aside
    id="sidebar"
    class="fixed top-0 left-0 z-40 w-72 h-screen transition-transform -translate-x-full sm:translate-x-0"
    aria-label="Sidebar"
  >
    <div class="h-full px-9 pt-8 overflow-y-auto bg-soft-blue">
      <div
        class="w-60 h-18 mb-8 rounded-lg border-b-1 border-gray-300 gap-2 bg-white"
      >
        <Link :href="route('dashboard')">
          <ApplicationMark class="block w-25" />
        </Link>
      </div>
      <ul class="mt-2 font-medium">
        <li>
          <a
            :href="route('dashboard')"
            :class="getSidebarLinkClass('dashboard')"
          >
            <img
              :class="getIconClass('dashboard')"
              class=""
              :src="getIconSrc('dashboard')"
              alt="home"
            />
            <span class="flex-1 ml-3 whitespace-nowrap">Dashboard</span>
          </a>
        </li>
        <li>
          <a
            :href="route('transactions.index')"
            :class="
              getSidebarLinkClass([
                'transactions.index',
                'transactions.payouts',
                'transactions.received',
                'transactions.show',
              ])
            "
          >
            <img
              :class="
                getIconClass([
                  'transactions.index',
                  'transactions.payouts',
                  'transactions.received',
                ])
              "
              class=""
              :src="
                getIconSrc(
                  [
                    'transactions.index',
                    'transactions.payouts',
                    'transactions.received',
                  ],
                  'transactions'
                )
              "
              alt="transactions"
            />
            <span class="flex-1 ml-3 whitespace-nowrap">Transactions</span>
          </a>
        </li>
        <li class="ml-3 my-3 text-sm font-normal leading-4 tracking-wider">
          MAKE PAYMENT
        </li>
        <li>
          <a
            :href="route('payouts.approvals')"
            :class="getSidebarLinkClass('payouts.approvals')"
          >
            <img
              :class="getIconClass('payouts.approvals')"
              :src="getIconSrc('payouts.approvals', 'approvals')"
              alt="approvals-icon"
            />
            <span class="flex-1 ml-3 whitespace-nowrap">Approvals</span>
          </a>
        </li>
        <!-- <li>
          <a
            href="#"
            :class="getSidebarLinkClass('payable.index')"
            style="background-color: inherit"
          >
            <img
              :class="getIconClass('payable.index')"
              :src="getIconSrc('payable')"
              alt="payable-icon"
            />
            <span
              class="flex-1 ml-3 whitespace-nowrap text-gray-300 cursor-not-allowed"
              >Payable</span
            >
          </a>
        </li> -->
        <li>
          <a
            :href="route('payouts.single-list')"
            :class="getSidebarLinkClass(['payouts.single-list'])"
          >
            <img
              :class="getIconClass('payouts.single-list')"
              :src="getIconSrc(['payouts.single-list'], 'payout')"
              alt="card-icon"
            />
            <span class="flex-1 ml-3 whitespace-nowrap">Payout</span>
          </a>
        </li>

        <li>
          <a
            :href="route('beneficiaries.list')"
            :class="getSidebarLinkClass('beneficiaries.list')"
          >
            <img
              :class="getIconClass('beneficiaries.list')"
              :src="getIconSrc('beneficiaries.list', 'beneficiaries')"
              alt="beneficiary-icon"
            />
            <span class="flex-1 ml-3 whitespace-nowrap">Beneficiaries</span>
          </a>
        </li>
        <li class="ml-3 my-3 text-sm font-normal leading-4 tracking-wider">
          RECEIVE PAYMENT
        </li>

        <li>
          <a
            :href="route('customers.index')"
            :class="
              getSidebarLinkClass([
                'customers.index',
                'customers.create.single',
                'customers.create.bulk',
              ])
            "
          >
            <img
              :class="
                getIconClass([
                  'customers.index',
                  'customers.create.single',
                  'customers.create.bulk',
                ])
              "
              :src="
                getIconSrc(
                  [
                    'customers.index',
                    'customers.create.single',
                    'customers.create.bulk',
                  ],
                  'customers'
                )
              "
              alt="customers-icon"
            />
            <span class="flex-1 ml-3 whitespace-nowrap">Customers</span>
          </a>
        </li>
        <li v-if="showPaymentLinks">
          <a
            :href="route('payment-links.index')"
            :class="
              getSidebarLinkClass([
                'payment-links.index',
                'payment-links.create',
                'payment-links.show',
              ])
            "
          >
            <img
              :class="
                getIconClass([
                  'payment-links.index',
                  'payment-links.create',
                  'payment-links.show',
                ])
              "
              :src="
                getIconSrc(
                  [
                    'payment-links.index',
                    'payment-links.create',
                    'payment-links.show',
                  ],
                  'paymentLinks'
                )
              "
              alt="payment-links-icon"
            />
            <span class="flex-1 ml-3 whitespace-nowrap">Payment Links</span>
          </a>
        </li>

        <li v-if="showQuickPay">
          <a
            :href="route('quick-pay.index')"
            :class="getSidebarLinkClass(['quick-pay.index'])"
            >
            <img
              :class="getIconClass(['quick-pay.index'])"
              :src="getIconSrc(['quick-pay.index'], 'quickPay')"
              alt="quick-pay-icon"
            />
            <span class="flex-1 ml-3 whitespace-nowrap">Quick Pay</span>
          </a>
        </li>

        <li>
          <a
            :href="route('invoices.index')"
            :class="
              getSidebarLinkClass([
                'invoices.index',
                'invoices.create',
                'invoices.show',
              ])
            "
          >
            <img
              :class="
                getIconClass([
                  'invoices.index',
                  'invoices.create',
                  'invoices.show',
                ])
              "
              :src="
                getIconSrc(
                  ['invoices.index', 'invoices.create', 'invoices.show'],
                  'invoices'
                )
              "
              alt="invoices-icon"
            />
            <span class="flex-1 ml-3 whitespace-nowrap">Invoices</span>
          </a>
        </li>
        <li class="ml-3 my-3 text-sm font-normal leading-4 tracking-wider">
          OTHERS
        </li>
        <li>
          <a
            :href="route('integrations.index')"
            :class="getSidebarLinkClass('integrations.index')"
          >
            <img
              :class="getIconClass('integrations.index')"
              :src="getIconSrc('integrations.index', 'integrations')"
              alt="integrations-icon"
            />
            <span class="flex-1 ml-3 whitespace-nowrap">Integrations</span>
          </a>
        </li>
        <li>
          <a
            :href="route('activity-logs.index')"
            :class="getSidebarLinkClass('activity-logs.index')"
          >
            <img
              :class="getIconClass('activity-logs.index')"
              :src="getIconSrc('activity-logs.index', 'activitylogs')"
              alt="activity-log-icon"
            />
            <span class="flex-1 ml-3 whitespace-nowrap">Activity Log</span>
          </a>
        </li>
        <li>
          <a
            :href="route('settings.profile-information')"
            :class="
              getSidebarLinkClass([
                'settings.api-configuration',
                'settings.profile-information',
                'settings.workflows.index',
                'settings.workflows.create',
                'settings.workflows.edit',
                'settings.preferences',
              ])
            "
          >
            <img
              :class="
                getIconClass([
                  'settings.api-configuration',
                  'settings.profile-information',
                  'settings.workflows.index',
                  'settings.workflows.create',
                  'settings.workflows.edit',
                  'settings.preferences',
                ])
              "
              :src="
                getIconSrc(
                  [
                    'settings.api-configuration',
                    'settings.profile-information',
                    'settings.workflows.index',
                    'settings.workflows.create',
                    'settings.workflows.edit',
                    'settings.preferences',
                  ],
                  'settings'
                )
              "
              alt="settings-icon"
            />
            <span class="flex-1 ml-3 whitespace-nowrap">Settings</span>
          </a>
        </li>
      </ul>
    </div>
  </aside>
  <div>
    <Head :title="title" />

    <Banner />

    <div class="sm:ml-72 min-h-screen bg-white w-auto">
      <nav class="bg-white border-b border-gray-100">
        <Link :href="route('dashboard')">
          <ApplicationMark class="block w-25 sm:hidden" />
        </Link>
        <!-- Primary Navigation Menu -->
        <div class="px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-20">
            <div class="flex items-center">
              <div>
                <h2 class="font-medium text-xl text-gray-800 leading-tight">
                  {{ title }}
                </h2>
              </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ml-6">
              <div class="ml-3 relative">
                <Dropdown align="right" width="60">
                  <template #trigger>
                    <span class="inline-flex rounded-md">
                      <button
                        type="button"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-pay-blue hover:bg-blue-900 focus:bg-blue-900 focus:outline-none transition ease-in-out duration-150"
                      >
                        Quick Action

                        <svg
                          class="ml-2 -mr-0.5 h-4 w-4"
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke-width="1.5"
                          stroke="currentColor"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                          />
                        </svg>
                      </button>
                    </span>
                  </template>

                  <template #content>
                    <div class="w-60">
                      <!-- Team Management -->
                      <!-- <template> -->
                      <DropdownLink
                        as="button"
                        @click.prevent="showPayoutOptionsModal = true"
                      >
                        New Payout Request
                      </DropdownLink>
                      <DropdownLink :href="route('invoices.create')">
                        Create Invoice
                      </DropdownLink>
                      <!-- </template> -->
                    </div>
                  </template>
                </Dropdown>
              </div>

              <div class="ml-3 relative" id="toggle-container"></div>

              <!-- Settings Dropdown -->
              <div class="ml-3 relative">
                <Dropdown align="right" width="48">
                  <template #trigger>
                    <button
                      v-if="$page.props.jetstream.managesProfilePhotos"
                      class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition"
                    >
                      <img
                        class="h-8 w-8 rounded-full object-cover"
                        :src="$page.props.auth.user.profile_photo_url"
                        :alt="$page.props.auth.user.name"
                      />
                    </button>

                    <span v-else class="inline-flex rounded-md">
                      <button
                        type="button"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150"
                      >
                        <!-- {{ $page.props.auth.user.name }} -->

                        <img
                          class="h-8 w-8 rounded-full object-cover"
                          src="../../icons/avatar.svg"
                          :alt="$page.props.auth.user.name"
                        />

                        <svg
                          class="ml-2 -mr-0.5 h-4 w-4"
                          xmlns="http://www.w3.org/2000/svg"
                          fill="none"
                          viewBox="0 0 24 24"
                          stroke-width="1.5"
                          stroke="currentColor"
                        >
                          <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M19.5 8.25l-7.5 7.5-7.5-7.5"
                          />
                        </svg>
                      </button>
                    </span>
                  </template>

                  <template #content>
                    <!-- Account Management -->
                    <div class="block px-4 py-2 text-xs text-gray-400">
                      Manage Account
                    </div>

                    <div class="border-t border-gray-200" />

                    <!-- Authentication -->
                    <form @submit.prevent="logout">
                      <DropdownLink as="button"> Log Out </DropdownLink>
                    </form>
                  </template>
                </Dropdown>
              </div>
            </div>

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
              <button
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                @click="showingNavigationDropdown = !showingNavigationDropdown"
              >
                <svg
                  class="h-6 w-6"
                  stroke="currentColor"
                  fill="none"
                  viewBox="0 0 24 24"
                >
                  <path
                    :class="{
                      hidden: showingNavigationDropdown,
                      'inline-flex': !showingNavigationDropdown,
                    }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                  />
                  <path
                    :class="{
                      hidden: !showingNavigationDropdown,
                      'inline-flex': showingNavigationDropdown,
                    }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                  />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div
          :class="{
            block: showingNavigationDropdown,
            hidden: !showingNavigationDropdown,
          }"
          class="sm:hidden"
        >
          <div class="pt-2 pb-3 space-y-1">
            <ResponsiveNavLink
              :href="route('dashboard')"
              :active="route().current('dashboard')"
            >
              Dashboard
            </ResponsiveNavLink>
          </div>

          <div class="pt-4 pb-1 border-t border-gray-200">
            <ResponsiveNavLink :href="route('payouts.create')">
              New Payout
            </ResponsiveNavLink>
          </div>

          <!-- Responsive Settings Options -->
          <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
              <div
                v-if="$page.props.jetstream.managesProfilePhotos"
                class="shrink-0 mr-3"
              >
                <img
                  class="h-10 w-10 rounded-full object-cover"
                  :src="$page.props.auth.user.profile_photo_url"
                  :alt="$page.props.auth.user.name"
                />
              </div>

              <div>
                <div class="font-medium text-base text-gray-800">
                  {{ $page.props.auth.user.name }}
                </div>
                <div class="font-medium text-sm text-gray-500">
                  {{ $page.props.auth.user.email }}
                </div>
              </div>
            </div>

            <div class="mt-3 space-y-1">
              <ResponsiveNavLink
                v-if="$page.props.jetstream.hasApiFeatures"
                :href="route('api-tokens.index')"
                :active="route().current('api-tokens.index')"
              >
                API Tokens
              </ResponsiveNavLink>

              <!-- Authentication -->
              <form method="POST" @submit.prevent="logout">
                <ResponsiveNavLink as="button"> Log Out </ResponsiveNavLink>
              </form>

              <!-- Team Management -->
              <template v-if="$page.props.jetstream.hasTeamFeatures">
                <div class="border-t border-gray-200" />

                <div class="block px-4 py-2 text-xs text-gray-400">
                  Manage Team
                </div>

                <!-- Team Settings -->
                <ResponsiveNavLink
                  :href="
                    route('teams.show', $page.props.auth.user.current_team)
                  "
                  :active="route().current('teams.show')"
                >
                  Team Settings
                </ResponsiveNavLink>

                <ResponsiveNavLink
                  v-if="$page.props.jetstream.canCreateTeams"
                  :href="route('teams.create')"
                  :active="route().current('teams.create')"
                >
                  Create New Team
                </ResponsiveNavLink>

                <!-- Team Switcher -->
                <template v-if="$page.props.auth.user.all_teams.length > 1">
                  <div class="border-t border-gray-200" />

                  <div class="block px-4 py-2 text-xs text-gray-400">
                    Switch Teams
                  </div>

                  <template
                    v-for="team in $page.props.auth.user.all_teams"
                    :key="team.id"
                  >
                    <form @submit.prevent="switchToTeam(team)">
                      <ResponsiveNavLink as="button">
                        <div class="flex items-center">
                          <svg
                            v-if="
                              team.id == $page.props.auth.user.current_team_id
                            "
                            class="mr-2 h-5 w-5 text-green-400"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                          >
                            <path
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            />
                          </svg>
                          <div>{{ team.name }}</div>
                        </div>
                      </ResponsiveNavLink>
                    </form>
                  </template>
                </template>
              </template>
            </div>
          </div>
        </div>
      </nav>

      <main class="px-4 sm:px-6 lg:px-8 mt-6">
        <slot />
      </main>
    </div>
  </div>
  <PayoutOptionsModal
    :show="showPayoutOptionsModal"
    @close="showPayoutOptionsModal = false"
  />

  <IdleLogoutModal
    :show="idleModalVisible"
    @stay-logged-in="stayLoggedIn"
    @logout="logout"
  />
</template>
<style>
@import "vue-select/dist/vue-select.css";
</style>
