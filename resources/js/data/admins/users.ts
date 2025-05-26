import { User } from '@/types';

// export const users = (): Promise<User[]> => {
//     return User[]
// }

export const getUsersByRole = async (role: string): Promise<User[] | unknown> => {
    try {
        const fetcher = await fetch('/admins/json/users/get-users-by-role/' + role, {
            method: 'get',
        });

        if (!fetcher.ok) {
            const response = await fetcher.json();
            console.log(response);
            throw Error('failed to get data');
        }

        const response = (await fetcher.json()).data as User[];
        return response;
    } catch (e) {
        return e;
    }
};

export const getUsersByUpline = async (id: string): Promise<User[] | unknown> => {
    try {
        const fetcher = await fetch('/admins/json/users/get-users-by-upline/' + id, {
            method: 'get',
        });

        if (!fetcher.ok) {
            const response = await fetcher.json();
            console.log(response);
            throw Error('failed to get data');
        }

        const response = (await fetcher.json()).data as User[];
        return response;
    } catch (e) {
        return e;
    }
};
