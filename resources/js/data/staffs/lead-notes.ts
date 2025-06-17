import { TLeadNote } from "@/types/custom";

export const getLeadNotesJson = async (leadId: number): Promise<unknown> => {
    const fetcher = await fetch(`/staffs/leads/${leadId}/notes`, {
        headers: {
            'X-Request-Format': 'json'
        },
    });
    let data = null;
    if (fetcher.ok) {
        data = await fetcher.json();
        return data as Promise<TLeadNote>;
    }
    return (await fetcher.json()) as Promise<{message: string | "failed to get data"}>;
}
