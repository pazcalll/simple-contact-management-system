import { User } from "."

type TLink = {
    active: boolean,
    label: string,
    url: string | null,
}

type TRole = {
    id: number,
    upline_id: number,
    name: string,
    guard_name: string,
    created_at: string,
    updated_at: string,
    upline?: TRole,
}

type TUser = User & {
    roles: TRole[],
    mobile_number: string,
}

type TPagination<T> = {
    current_page: number,
    data: T,
    first_page_url: string,
    from: number,
    last_page: number,
    last_page_url: string,
    links: TLink[],
    next_page_url: string | null,
    path: string,
    per_page: number,
    prev_page_url: null,
    to: number,
    total: number,
}

type TFlash = {
    flash: {
        success?: string,
        error?: string,
    }
}
