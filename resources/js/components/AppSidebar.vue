<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { CircleUserRound, LayoutGrid, PersonStanding, UserRound } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';
import { TUser } from '@/types/custom';
import { ROLE_ADMIN, ROLE_MANAGER, ROLE_STAFF, ROLE_SUPERVISOR, ROLE_TEAM_LEADER } from '@/consts/role';

type TAuth = { auth: { user: TUser } };

const page = usePage<TAuth>();
const user = page.props.auth.user;
const role = user.roles[0];

const mainNavItems: NavItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
    icon: LayoutGrid,
  },
];

if (role.name == ROLE_STAFF) {
  mainNavItems.push({
    title: 'Leads',
    href: '/staffs/leads',
    icon: PersonStanding,
  });
} else if (role.name == ROLE_MANAGER) {
  mainNavItems.push({
    title: 'Leads',
    href: '/managers/leads',
    icon: PersonStanding,
  });
  mainNavItems.push({
    title: 'Customers',
    href: '/managers/customers',
    icon: CircleUserRound,
  });
} else if (role.name == ROLE_SUPERVISOR) {
  mainNavItems.push({
    title: 'Leads',
    href: '/supervisors/leads',
    icon: PersonStanding,
  });
} else if (role.name == ROLE_TEAM_LEADER) {
  mainNavItems.push({
    title: 'Leads',
    href: '/team-leaders/leads',
    icon: PersonStanding,
  });
} else if (role.name == ROLE_ADMIN) {
  mainNavItems.push(
    {
      title: 'Users',
      href: '/admins/users',
      icon: UserRound,
    },
    {
      title: 'Leads',
      href: '/admins/leads',
      icon: PersonStanding,
    },
    {
      title: 'Customers',
      href: '/admins/customers',
      icon: CircleUserRound,
    },
  );
}

const footerNavItems: NavItem[] = [
  // {
  //     title: 'Github Repo',
  //     href: 'https://github.com/laravel/vue-starter-kit',
  //     icon: Folder,
  // },
  // {
  //     title: 'Documentation',
  //     href: 'https://laravel.com/docs/starter-kits',
  //     icon: BookOpen,
  // },
];
</script>

<template>
  <Sidebar collapsible="icon" variant="inset">
    <SidebarHeader>
      <SidebarMenu>
        <SidebarMenuItem>
          <SidebarMenuButton size="lg" as-child>
            <Link :href="route('dashboard')">
              <AppLogo />
            </Link>
          </SidebarMenuButton>
        </SidebarMenuItem>
      </SidebarMenu>
    </SidebarHeader>

    <SidebarContent>
      <NavMain :items="mainNavItems" />
    </SidebarContent>

    <SidebarFooter>
      <NavFooter :items="footerNavItems" />
      <NavUser />
    </SidebarFooter>
  </Sidebar>
  <slot />
</template>
