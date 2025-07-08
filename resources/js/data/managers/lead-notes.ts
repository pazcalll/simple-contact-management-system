import { TLeadNote } from "@/types/custom";

export const getLeadNotesJson = async (leadId: number): Promise<unknown> => {
    const fetcher = await fetch(`/managers/leads/${leadId}/lead-notes`, {
        headers: {
            'X-Request-Format': 'json'
        },
    });
    let data = null;
    if (fetcher.ok) {
        data = await fetcher.json();
        console.log("Lead Notes Data:", data);
        return data as Promise<TLeadNote>;
    }
    return (await fetcher.json()) as Promise<{message: string | "failed to get data"}>;
}
