import { User } from '.';

type TLink = {
    active: boolean;
    label: string;
    url: string | null;
};

type TRole = {
    id: number;
    upline_id: number;
    name: string;
    guard_name: string;
    created_at: string;
    updated_at: string;
    upline?: TRole;
};

type TUser = User & {
    roles: TRole[];
    mobile: string;
};

type TPagination<T> = {
    current_page: number;
    data: T;
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: TLink[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: null;
    to: number;
    total: number;
};

type TFlash = {
    flash: {
        success?: string;
        error?: string;
    };
};

type TLead = {
    id: number;
    name: string;
    email: string;
    mobile_number: string;
    utm_source: string;
    utm_medium: string;
    utm_campaign: string;
    is_private: boolean;
    lead_status_id: number;
    lead_status?: TLeadStatus;
    lead_notes?: TLeadNote[];
    users?: TUser[];
};

type TLeadNote = {
    id: number;
    lead_id: number;
    user_id: number;
    note: string;
    user: TUser;
    created_at: string;
    updated_at: string;
};

type TLeadStatus = {
    id: number;
    name: string;
    slug: string;
    group: string;
    color: string;
    is_active: boolean;
    sort_order: number;
    description: string;
};

type TStatus = {
    name: string;
    slug: string;
    group: string;
    color: string;
    is_active: boolean;
    sort_order: number;
    description: string;
}
